<?php

namespace App\EventSubscriber;

use App\Entity\Post;
use App\Entity\User;
use App\Entity\Comment;
use Symfony\Component\Mime\Email;
use App\Event\Post\CommentCreatedEvent;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class NotificationCommentSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly MailerInterface $mailer,
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly TranslatorInterface $translator,
        private readonly string $sender
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            CommentCreatedEvent::class => 'onCommentCreated',
        ];
    }

    public function onCommentCreated(CommentCreatedEvent $event): void
    {
        /** @var Comment $comment */
        $comment = $event->getComment();

        /** @var Post $post */
        $post = $comment->getPost();

        /** @var User $author */
        $author = $post->getAuthor();

        /** @var string $emailAddress */
        $emailAddress = $author->getEmail();

        $UrlToPost = $this->urlGenerator->generate('blog_show', [
            'slug' => $post->getSlug(),
            '_fragment' => 'comment_'.$comment->getId(),
        ], UrlGeneratorInterface::ABSOLUTE_URL);

        $subject = $this->translator->trans('notification.comment_created');
        $body = $this->translator->trans('notification.comment_created.description', [
            'title' => $post->getTitle(),
            'url' => $UrlToPost,
        ]);

        $email = (new Email())
            ->from($this->sender)
            ->to($emailAddress)
            ->subject($subject)
            ->html($body)
        ;

        $this->mailer->send($email);
    }
}
