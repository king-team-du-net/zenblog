<?php

/*
 * @package Symfony Framework
 *
 * @author App bloggy <robertdequidt@gmail.com>
 *
 * @copyright 2022-2023
 */

namespace App\Controller\Frontend;

use App\Controller\Controller;
use App\Entity\Ad;
use App\Entity\Booking;
use App\Entity\Comment;
use App\Entity\User;
use App\Event\Post\CommentCreatedEvent;
use App\Form\BookingType;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\Translation\TranslatorInterface;

class BookingController extends Controller
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly TranslatorInterface $translator,
        private readonly RequestStack $stack
    ) {
    }

    #[Route('/ads/{slug}/book', name: 'booking_create', methods: [Request::METHOD_GET, Request::METHOD_POST])]
    #[IsGranted('ROLE_USER')]
    public function book(
        Request $request,
        #[CurrentUser] User $user,
        Ad $ad
    ): Response {
        $booking = new Booking();
        $form = $this->createForm(BookingType::class, $booking)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $user = $this->getUser();

            // If the dates are not available, error message
            $booking->setBooker($user)->setAd($ad);
            if (!$booking->isBookableDates()) {
                $this->addFlash('warning', $this->translator->trans('flash_warning.booking_dates'));
            } else {
                // Otherwise save and redirect
                $this->em->persist($booking);
                $this->em->flush();

                return $this->redirectToRoute('booking_show', ['id' => $booking->getId(), 'withAlert' => true]);
            }
        }

        return $this->render('frontend/booking/book.html.twig', compact('ad', 'form'));
    }

    /** Allows you to display the page of a reservation */
    #[Route('/booking/{id}', name: 'booking_show', methods: [Request::METHOD_GET])]
    public function show(
        Request $request,
        EventDispatcherInterface $eventDispatcher,
        CommentRepository $repository,
        Booking $booking
    ): Response {
        // Find recent comments approved
        $comments = $repository->findRecentComments($booking);

        // Create a new comment
        $comment = new Comment();
        $commentform = $this->createForm(CommentType::class, $comment)->handleRequest($request);

        if ($commentform->isSubmitted() && $commentform->isValid()) {
            $comment
                ->setIp($this->stack->getMainRequest()?->getClientIp())
                ->setIsApproved(false)
                ->setIsRGPD(true)
                ->setPublishedAt(new \DateTime('now'))
                ->setAd($booking->getAd())
                ->setAuthor($this->getUser())
            ;

            $parentid = $commentform->get('parentid')->getData();
            if (null !== $parentid) {
                $parent = $this->em->getRepository(Comment::class)->find($parentid);
            }

            $comment->setParent($parent ?? null);

            $this->em->persist($comment);
            $this->em->flush();

            $eventDispatcher->dispatch(new CommentCreatedEvent($comment));
            $this->addFlash('success', $this->translator->trans('flash_success.comments_successfully'));
        }

        return $this->render('frontend/booking/show.html.twig', compact('booking', 'commentForm'));
    }
}
