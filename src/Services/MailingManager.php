<?php

namespace App\Services;

use Psr\Cache\InvalidArgumentException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Contracts\Cache\CacheInterface;

class MailingManager
{
    private CacheInterface $cache;
    private MailerInterface $mailer;

    public function __construct(CacheInterface $cache, MailerInterface $mailer)
    {
        $this->cache = $cache;
        $this->mailer = $mailer;
    }

    /**
     * Checks if the user is flooding.
     *
     * @param Request $request
     * @return bool Returns true if the user is not flooding, false if they are.
     * @throws InvalidArgumentException
     */
    public function isUserFlooding(Request $request): bool
    {
        $ip = $request->getClientIp();
        $rateLimitWindow = 21600; // 6 hours in seconds
        $cacheKey = 'email_rate_limit_' . $ip;

        $currentTime = time();
        $cacheItem = $this->cache->getItem($cacheKey);

        if ($cacheItem->isHit()) {
            $lastEmailTime = $cacheItem->get();
            if ($currentTime - $lastEmailTime < $rateLimitWindow) {
                return false; // User is flooding
            }
        }

        // Update the cache with the current timestamp
        $cacheItem->set($currentTime);
        $cacheItem->expiresAfter($rateLimitWindow);
        $this->cache->save($cacheItem);

        return true; // User is not flooding
    }

    /**
     * Sends an email using the form data.
     *
     * @param mixed $form The form containing the email data.
     * @return bool Returns true if the email was sent successfully.
     * @throws TransportExceptionInterface If an error occurs while sending the email.
     */
    public function sendEmail(mixed $form): bool
    {
        $formData = $form->getData();

        $email = $this->createEmail($formData);
        $this->mailer->send($email);

        return true;
    }

    /**
     * Creates an Email object from form data.
     *
     * @param array $formData The form data.
     * @return Email The created Email object.
     */
    private function createEmail(array $formData): Email
    {
        return (new Email())
            ->from($formData['email'])
            ->to('ugo.armer@gmail.com')
            ->subject('Nuevo mensaje recibido desde tu portafolio')
            ->text("Name: {$formData['name']}\nEmail: {$formData['email']}\nMessage: {$formData['message']}");
    }
}