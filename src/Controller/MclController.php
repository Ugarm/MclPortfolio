<?php

namespace App\Controller;

use App\Entity\Content;
use App\Entity\Socials;
use App\Services\MailingManager;
use App\Form\ContactType;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Cache\InvalidArgumentException;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Cache\CacheInterface;

final class MclController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private CacheInterface $cache;
    private LoggerInterface $logger;

    public function __construct(
        EntityManagerInterface $entityManager,
        CacheInterface $cache,
        LoggerInterface $logger
    ) {
        $this->entityManager = $entityManager;
        $this->cache = $cache;
        $this->logger = $logger;
    }

    /**
     * @throws InvalidArgumentException
     */
    #[Route('/', name: 'app_mcl')]
    public function index(Request $request, MailingManager $mailingManager): Response
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($mailingManager->isUserFlooding($request)) {
                try {
                    $mailingManager->sendEmail($form);

                    // TODO : Make it possible to set the expected wait time and let it get back to normal dynamically (from 17 to 2 days during a two weeks holiday for instance)
                    $this->addFlash('success', 'Thanks for reaching out! You can expect an answer in under two business days :)');
                    return $this->redirectToRoute('app_mcl');
                } catch (\Exception $e) {
                    $this->logger->error('Email sending failed: ' . $e->getMessage(), [
                        'exception' => $e,
                        'form_data' => $form->getData(),
                    ]);

                    $this->addFlash('error', 'Something went wrong, please try again later.');
                    return $this->redirectToRoute('app_mcl');
                }
            } else {
                $this->addFlash('error', 'You have already sent an email, please allow me two business days to answer.');
                return $this->redirectToRoute('app_mcl');
            }
        }

        $content = $this->entityManager->getRepository(Content::class)->findAll();
        $socials = $this->entityManager->getRepository(Socials::class)->find(1);

        return $this->render('mcl/index.html.twig', [
            'controller_name' => 'MclController',
            'content' => $content,
            'socials' => $socials,
            'form' => $form->createView(),
        ]);
    }
}