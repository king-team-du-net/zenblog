<?php

declare(strict_types=1);

namespace App\Interface\Mailer\Mail;

use App\Entity\Post;
use Symfony\Component\Mime\Address;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

final class PostSharedMail implements MailInterface
{
    public function __construct(
        private readonly ParameterBagInterface $params
    ) {
    }

    public function build(TemplatedEmail $email, array $options = []): void
    {
        $options = [];
        /** @var Post $post */
        $post = new Post;
        $subject = sprintf('%s recommends you to read "%s"', $options['sender_name'], $post->getTitle());

        $email
            /*
            ->from(new Address(
                $this->params->get('website_no_reply_email'),
                $this->params->get('website_name'),
            ))*/
            ->to(new Address($options['receiver_email']))
            ->subject($subject)
            ->htmlTemplate('emails/blog-show-shared.html.twig')
            ->context([
                'post' => $post,
                'sender_name' => $options['sender_name'],
                'sender_comments' => $options['sender_comments'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired(['post']);
        $resolver->setAllowedTypes('post', Post::class);
    }
}
