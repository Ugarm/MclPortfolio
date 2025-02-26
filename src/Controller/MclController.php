<?php

namespace App\Controller;

use App\Entity\Content;
use App\Form\ContactType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Attribute\Route;

final class MclController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private ContactType $contactType;

    public function __construct(EntityManagerInterface $entityManager, ContactType $contactType)
    {
        $this->entityManager = $entityManager;
        $this->contactType = $contactType;
    }

    /**
     * @throws TransportExceptionInterface
     */
    #[Route('/', name: 'app_mcl')]
    public function index(Request $request, MailerInterface $mailer): Response
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();

            $email = (new Email())
                ->from($formData['email'])
                ->to('m-cl@outlook.com')
                ->subject('New contact form submission')
                ->text("Name: {$formData['name']}\nEmail: {$formData['email']}\nMessage: {$formData['message']}");

            $mailer->send($email);

            if ($this->getParameter('kernel.environment') === 'dev') {
                $this->addFlash('success', 'Email sent (in development mode). Check the mail icon in the Symfony toolbar to view it.');
            } else {
                $this->addFlash('success', 'Your message has been sent!');
            }

            return $this->redirectToRoute('app_mcl');
        }

        $content = $this->entityManager->getRepository(Content::class)->findAll();

        return $this->render('mcl/index.html.twig', [
            'controller_name' => 'MclController',
            'content' => $content,
            'form' => $form
        ]);
    }
}
