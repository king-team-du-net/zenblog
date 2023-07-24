<?php

/*
 * @package Symfony Framework
 *
 * @author App bloggy <robertdequidt@gmail.com>
 *
 * @copyright 2022-2023
 */

namespace App\Controller\Dashboard\Administrator;

use App\Controller\Controller;
use App\Entity\User;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted(User::ADMINISTRATOR)]
class LanguageController extends Controller
{
    public function index(): void
    {
        // code...
    }

    public function addedit(): void
    {
        // code...
    }

    public function delete(): void
    {
        // code...
    }

    public function restore(): void
    {
        // code...
    }

    public function showhide(): void
    {
        // code...
    }
}
