<?php

/*
 * @package Symfony Framework
 *
 * @author App bloggy <robertdequidt@gmail.com>
 *
 * @copyright 2022-2023
 */

namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Contracts\Translation\TranslatorInterface;

final class PictureService
{
    public function __construct(
        private readonly ParameterBagInterface $params,
        private readonly TranslatorInterface $translator
    ) {
    }

    public function add(UploadedFile $uploadedFile, ?string $folder = '', ?int $width = 250, ?int $height = 250): string
    {
        /**
         * Give a new name to the image.
         */
        $file = md5(uniqid(rand(), true)).'.webp';

        /**
         * We retrieve the information of the image.
         */
        $uploadedFileInfo = getimagesize($uploadedFile);

        if (false === $uploadedFileInfo) {
            throw new \Exception($this->translator->trans('throw.picture_service_uploaded_file_info'));
        }

        /*
         * We check the format of the image
         */
        switch ($uploadedFileInfo['mime']) {
            case 'image/png':
                $uploadedFileSource = imagecreatefrompng($uploadedFile);
                break;
            case 'image/jpeg':
                $uploadedFileSource = imagecreatefromjpeg($uploadedFile);
                break;
            case 'image/webp':
                $uploadedFileSource = imagecreatefromwebp($uploadedFile);
                break;
            default:
                throw new \Exception($this->translator->trans('throw.picture_service_uploaded_file_info'));
        }

        /**
         * Crop the image
         * We get the dimensions.
         */
        $imageWidth = $uploadedFileInfo[0];
        $imageHeight = $uploadedFileInfo[1];

        /*
         * Check the orientation of the image
         */
        switch ($imageWidth <=> $imageHeight) {
            case -1: // Portrait
                $squareSize = $imageWidth;
                $src_x = 0;
                $src_y = ($imageHeight - $squareSize) / 2;
                break;
            case 0: // Square
                $squareSize = $imageWidth;
                $src_x = 0;
                $src_y = 0;
                break;
            case 1: // Landscape
                $squareSize = $imageHeight;
                $src_x = ($imageWidth - $squareSize) / 2;
                $src_y = 0;
                break;
        }

        /**
         * We create a new "blank" image.
         */
        $uploadedFileResized = imagecreatetruecolor($width, $height);

        imagecopyresampled($uploadedFileResized, $uploadedFileSource, 0, 0, $src_x, $src_y, $width, $height, $squareSize, $squareSize);

        $path = $this->params->get('images_directory').$folder;

        /*
         * We create the destination folder if it does not exist
         */
        if (!file_exists($path.'/small/')) {
            mkdir($path.'/small/', 0755, true);
        }

        /*
         * We store the cropped image
         */
        imagewebp($uploadedFileResized, $path.'/small/'.$width.'x'.$height.'-'.$file);

        $uploadedFile->move($path.'/', $file);

        return $file;
    }

    public function delete(string $file, ?string $folder = '', ?int $width = 250, ?int $height = 250): bool
    {
        if ('default.webp' !== $file) {
            $success = false;
            $path = $this->params->get('images_directory').$folder;

            $small = $path.'/small/'.$width.'x'.$height.'-'.$file;
            if (file_exists($small)) {
                unlink($small);
                $success = true;
            }

            $original = $path.'/'.$file;
            if (file_exists($original)) {
                unlink($original);
                $success = true;
            }

            return $success;
        }

        return false;
    }
}
