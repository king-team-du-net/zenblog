<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

#[Route(path: '/picture', name: 'picture_')]
class ResizerPictureController extends AbstractController
{
    private readonly string $cachePath;
    private readonly string $resizeKey;
    private readonly string $publicPath;

    public function __construct(ParameterBagInterface $param)
    {
        $projectDir = $param->get('kernel.project_dir');
        $resizeKey = $param->get('resize_picture_key');

        if (!is_string($projectDir)) {
            throw new \RuntimeException('Parameter kernel.project_dir is not a string');
        }

        if (!is_string($resizeKey)) {
            throw new \RuntimeException('Parameter resize_picture_key is not a string');
        }

        $this->cachePath = $projectDir.'/var/images';
        $this->publicPath = $projectDir.'/public';
        $this->resizeKey = $resizeKey;
    }

    #[Route(
        path: '/resize/{width}/{height}/{path}',
        name: 'resizer',
        methods: [Request::METHOD_GET],
        requirements: ['width' => '\d+', 'height' => '\d+', 'path' => '.+']
    )]
    public function resizerPicture(Request $request, int $width, int $height, string $path): void
    {
        // code
    }

    #[Route(path: '/convert/{path}', name: 'jpg', methods: [Request::METHOD_GET], requirements: ['path' => '.+'])]
    public function convertPicture(Request $request, string $path): void
    {
        // code
    }
}
