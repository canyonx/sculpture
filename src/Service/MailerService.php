<?php

namespace App\Service;

use Symfony\Component\Mime\Email;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\NotificationEmail;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class MailerService
{
    public function __construct(
        private MailerInterface $mailer,
        #[Autowire('%admin_email%')] private string $adminEmail,
    ) {
    }

    public function sendEmail(
        $subject = '',
        $context = []
    ): void {

        // dd($context);
        $email = (new NotificationEmail())
            ->from($context["message"]["email"])
            ->to($this->adminEmail)
            ->subject($subject)
            ->context($context)
            ->htmlTemplate('emails/message_notification.html.twig');
        $this->mailer->send($email);
    }
}
