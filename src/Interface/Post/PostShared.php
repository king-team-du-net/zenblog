<?php

declare(strict_types=1);

namespace App\Interface\Post;

use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;
use App\Interface\Mailer\Mail\PostSharedMail;
use App\Interface\Mailer\MailSenderInterface;

final class PostShared implements PostSharedInterface
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly MailSenderInterface $mailSender
    ) {
    }

    public function __invoke(Post $post, array $data = []): void
    {
        $this->mailSender->with('post', $post, 'data', $data)->send(PostSharedMail::class);
    }
}
