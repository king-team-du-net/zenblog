<?php

declare(strict_types=1);

/*
 * @package Symfony Framework
 *
 * @author App bloggy <robertdequidt@gmail.com>
 *
 * @copyright 2022-2023
 */

namespace App\Controller\Frontend;

use App\Entity\Ad;
use App\Entity\User;
use App\Form\AdType;
use App\Interface\Ad\Create\CreateAdInterface;
use App\Interface\Ad\Delete\DeleteAdInterface;
use App\Interface\Ad\Update\EditAdInterface;
use App\Repository\AdRepository;
use App\Security\Voter\AdsVoter;
use App\Security\Voter\AdVoter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\Translation\TranslatorInterface;

class AdController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly TranslatorInterface $translator
    ) {
    }

    #[Route('/ads', name: 'ads_list', methods: [Request::METHOD_GET])]
    public function list(AdRepository $adRepository): Response
    {
        $ads = $adRepository->findBy(['isPublished' => true], ['createdAt' => 'DESC']);

        return $this->render('frontend/ad/list.html.twig', compact('ads'));
    }

    #[Route('/ads/create', name: 'ads_create', methods: [Request::METHOD_GET, Request::METHOD_POST])]
    // #[IsGranted('ROLE_USER')]
    #[IsGranted(AdVoter::AD_CREATE)]
    public function create(Request $request, #[CurrentUser] User $user, CreateAdInterface $createAd): Response
    {
        $ad = new Ad();
        $ad->setAuthor($user);

        $form = $this->createForm(
            AdType::class,
            $ad,
            ['validation_groups' => ['cover', 'image', 'Default']]
        )->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $createAd($ad);

            $this->addFlash('success', $this->translator->trans('flash_success.ad_create_successfully'));

            return $this->redirectToRoute('ads_show', [
                'slug' => $ad->getSlug(),
            ]);
        }

        return $this->render('frontend/ad/create.html.twig', compact('ad', 'form'));
    }

    #[Route('/ads/{slug}/edit', name: 'ads_edit', methods: [Request::METHOD_GET, Request::METHOD_POST])]
    #[IsGranted(AdVoter::AD_EDIT, subject: 'ad')]
    // #[IsGranted(AdsVoter::EDIT, subject: 'ad')]
    public function edit(Request $request, Ad $ad, EditAdInterface $editAd): Response
    {
        $form = $this->createForm(AdType::class, $ad)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $editAd($ad);

            $this->addFlash('success', $this->translator->trans('flash_success.ad_edit_successfully'));

            return $this->redirectToRoute('ads_show', [
                'slug' => $ad->getSlug(),
            ]);
        }

        return $this->render('frontend/ad/edit.html.twig', compact('ad', 'form'));
    }

    #[Route('/ads/{slug}', name: 'ads_show', methods: [Request::METHOD_GET])]
    public function show(Ad $ad): Response
    {
        if (!$ad) {
            $this->addFlash('danger', $this->translator->trans('ad.no_ads_found'));

            return $this->redirectToRoute('ads_list');
        }

        $ad->viewed();
        $this->em->persist($ad);
        $this->em->flush();

        return $this->render('frontend/ad/show.html.twig', compact('ad', 'form'));
    }

    #[Route('/ads/{slug}/delete', name: 'ads_delete', methods: [Request::METHOD_GET, Request::METHOD_POST])]
    #[IsGranted(AdVoter::AD_DELETE, subject: 'ad')]
    // #[IsGranted(AdsVoter::DELETE, subject: 'ad')]
    public function delete(Request $request, Ad $ad, DeleteAdInterface $deleteAd): Response
    {
        $form = $this->createFormBuilder()->getForm()->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $deleteAd($ad);

            $this->addFlash('success', $this->translator->trans('flash_success.ad_delete_successfully'));

            return $this->redirectToRoute('ads_list');
        }

        return $this->render('frontend/ad/delete.html.twig', compact('ad', 'form'));
    }
}
