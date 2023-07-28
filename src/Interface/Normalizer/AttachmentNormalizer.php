<?php

declare(strict_types=1);

/*
 * @package Symfony Framework
 *
 * @author App bloggy <robertdequidt@gmail.com>
 *
 * @copyright 2022-2023
 */

namespace App\Interface\Normalizer;

use App\Entity\Image\Attachment;
use App\Interface\Uploads\Picture\ResizerPicture;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

class AttachmentNormalizer extends Normalizer
{
    public function __construct(
        private readonly UploaderHelper $uploaderHelper,
        private readonly ResizerPicture $resizer
    ) {
    }

    /**
     * @param Attachment $object
     */
    public function normalize(mixed $object, string $format = null, array $context = []): array
    {
        $info = pathinfo($object->getAttachmentName());
        $attachmentParts = explode('-', $info['attachmentName']);
        $attachmentParts = \array_slice($attachmentParts, 0, -1);
        $attachmentName = implode('-', $attachmentParts);
        $extension = $info['extension'] ?? '';

        return [
            'id' => $object->getId(),
            'createdAt' => $object->getCreatedAt()->getTimestamp(),
            'name' => "{$attachmentName}.{$extension}",
            'size' => $object->getAttachmentSize(),
            'url' => $this->uploaderHelper->asset($object),
            'thumbnail' => $this->resizer->resize($this->uploaderHelper->asset($object), 250, 100),
        ];
    }

    /**
     * @param mixed $data ;
     */
    public function supportsNormalization(mixed $data, string $format = null, array $context = []): bool
    {
        return $data instanceof Attachment && 'json' === $format;
    }
}
