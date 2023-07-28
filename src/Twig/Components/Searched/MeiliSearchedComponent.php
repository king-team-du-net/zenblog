<?php

declare(strict_types=1);

/*
 * @package Symfony Framework
 *
 * @author App bloggy <robertdequidt@gmail.com>
 *
 * @copyright 2022-2023
 */

namespace App\Twig\Components\Searched;

use App\Entity\Post;
use App\Repository\PostRepository;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent(name: 'meili-searched', template: 'components/searched/meili-searched.html.twig')]
final class MeiliSearchedComponent
{
    use DefaultActionTrait;

    #[LiveProp(writable: true)]
    public string $query = '';

    public function __construct(private readonly PostRepository $postRepository)
    {
    }

    /**
     * @return array<Post>
     */
    public function getResult(): array
    {
        return $this->postRepository->findBySearchedQuery($this->query);
    }
}
