<?php

declare(strict_types=1);

namespace App\Event\Content;

use App\Entity\Content;

class ContentUpdatedEvent
{
    public function __construct(
        private readonly Content $content,
        private readonly Content $previous
    ) {
    }

    public function getContent(): Content
    {
        return $this->content;
    }

    public function getPrevious(): Content
    {
        return $this->previous;
    }
}
