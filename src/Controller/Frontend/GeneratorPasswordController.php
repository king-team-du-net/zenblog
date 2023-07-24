<?php

/*
 * @package Symfony Framework
 *
 * @author App bloggy <robertdequidt@gmail.com>
 *
 * @copyright 2022-2023
 */

namespace App\Controller\Frontend;

use App\Entity\Data\PasswordRequirementData;
use App\Form\PasswordRequirementDataType;
use App\Utils\GeneratorPassword;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GeneratorPasswordController extends AbstractController
{
    #[Route(path: '/generatorTool', name: 'generator_tool', methods: [Request::METHOD_GET, Request::METHOD_POST])]
    public function generatorToolAction(Request $request): Response
    {
        $form = $this->createForm(PasswordRequirementDataType::class)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $passwordRequirementData = $form->getData();

            $request->getSession()->set('generator_password_requirement_data', $passwordRequirementData);

            return $this->redirectToRoute('generator_password');
        }

        return $this->render('frontend/generator/generator-tool.html.twig', compact('form'));
    }

    #[Route(path: '/generatedPassword', name: 'generator_password', methods: [Request::METHOD_GET])]
    public function generatedPasswordAction(Request $request): Response
    {
        $passwordRequirementData = $request->getSession()->get('generator_password_requirement_data');

        if (!$passwordRequirementData) {
            return $this->redirectToRoute('homepage');
        }

        $password = GeneratorPassword::fromPasswordRequirementData($passwordRequirementData);

        $response = $this->render('frontend/generator/password.html.twig', compact('password'));

        $this->savePasswordRequirementData($response, $passwordRequirementData);

        return $response;
    }

    private function savePasswordRequirementData(
        Response $response, PasswordRequirementData $passwordRequirementData
    ): void {
        $fiveYearsFromNow = new \DateTimeImmutable('+5 years');

        $response->headers->setCookie(
            new Cookie('generator_password_length', $passwordRequirementData->getLength(), $fiveYearsFromNow)
        );

        $response->headers->setCookie(
            new Cookie('generator_password_uppercase_letters', $passwordRequirementData->getUppercaseLetters() ? '1' : '0', $fiveYearsFromNow)
        );

        $response->headers->setCookie(
            new Cookie('generator_password_digits', $passwordRequirementData->getDigits() ? '1' : '0', $fiveYearsFromNow)
        );

        $response->headers->setCookie(
            new Cookie('generator_password_special_characters', $passwordRequirementData->getSpecialCharacters() ? '1' : '0', $fiveYearsFromNow)
        );
    }
}
