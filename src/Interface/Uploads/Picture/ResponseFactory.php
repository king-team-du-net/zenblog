<?php

declare(strict_types=1);

/*
 * @package Symfony Framework
 *
 * @author App bloggy <robertdequidt@gmail.com>
 *
 * @copyright 2022-2023
 */

namespace App\Interface\Uploads\Picture;

use League\Flysystem\FilesystemOperator;
use League\Glide\Responses\ResponseFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class ResponseFactory implements ResponseFactoryInterface
{
    public function __construct(protected ?Request $request = null)
    {
    }

    /**
     * Create the response.
     *
     * @param FilesystemOperator $cache the cache file system
     * @param string             $path  the cached file path
     */
    public function create(FilesystemOperator $cache, $path): StreamedResponse
    {
        $stream = $cache->readStream($path);

        $response = new StreamedResponse();
        $response->headers->set('Content-Type', $cache->mimeType($path));
        $response->headers->set('Content-Length', (string) $cache->fileSize($path));
        $response->setPublic();
        $response->setMaxAge(31_536_000);
        $response->setExpires(new \DateTime('+ 1 years'));

        if ($this->request) {
            $response->setLastModified(new \DateTime(sprintf('@%s', $cache->lastModified($path))));
            $response->isNotModified($this->request);
        }

        $response->setCallback(function () use ($stream) {
            if (0 !== ftell($stream)) {
                rewind($stream);
            }
            fpassthru($stream);
            fclose($stream);
        });

        return $response;
    }
}
