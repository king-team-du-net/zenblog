<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Entity\Post;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
//use Symfony\Component\Workflow\Event\Event;

final class PostSubscriber implements EventSubscriberInterface
{
    public function onCompletePublish(Event $event): void
    {
        /** @var Post $post */
        $post = $event->getSubject();
        $post->setPublishedAt(new \DateTimeImmutable());
    }

    /**
     * @return array<string, string>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            'workflow.blogpost.completed.publish' => 'onCompletePublish',
        ];
    }
}
