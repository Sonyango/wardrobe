<?php

namespace App\Services;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerificationCodeMail;

class VerificationMailService
{
    protected BrevoMailer $brevoMailer;

    public function __construct(BrevoMailer $brevoMailer)
    {
        $this->brevoMailer = $brevoMailer;
    }

    /**
     * send verification code using the appropriate mailer
     * based on the current environment.
     *
     * - local/testing -> Mailtrap via Laravel's Mail facade (SMTP)
     * - production -> Brevo HTTP API (no port restriction)
     */
    public function send(string $toEmail, string $toName, string $code): void
    {
        if (app()->environment('production')) {
            $htmlContent = VerificationEmailTemplate::build($code, config('app.name'));

            $this->brevoMailer->send(
                toEmail: $toEmail,
                toName: $toName,
                subject: 'Your ' . config('app.name') . ' Verification Code',
                htmlContent: $htmlContent
            );
        } else {
            Mail::to($toEmail)->send(new VerificationCodeMail($toName, $code));
        }
    }
}
