<?php

namespace App\Controller\Dashboard\Administrator;

use App\Entity\User;
use App\Entity\Setting;
use App\Controller\Controller;
use App\Twig\TwigSettingExtension;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

#[IsGranted(User::ADMINISTRATOR)]
class LanguageController extends Controller
{
    public function index(): void
    {
        # code...
    }

    public function addedit(): void
    {
        # code...
    }

    public function delete(): void
    {
        # code...
    }

    public function restore(): void
    {
        # code...
    }

    public function showhide(): void
    {
        # code...
    }
}
