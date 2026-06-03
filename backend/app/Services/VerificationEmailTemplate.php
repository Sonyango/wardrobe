<?php

namespace App\Services;

class VerificationEmailTemplate
{
    public static function build(string $code, string $appName): string
    {
        return "
        <!DOCTYPE html>
        <html>
        <body style='font-family: Arial, sans-serif; background: #f4f4f4; padding: 30px;'>
            <div style='max-width: 480px; margin: auto; background: #fff;
                        border-radius: 8px; padding: 32px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);'>

                <h2 style='color: #333;'>Verify your email</h2>

                <p style='color: #555; font-size: 15px;'>
                    Thank you for registering with <strong>{$appName}</strong>.
                    Use the verification code below to complete your registration:
                </p>

                <div style='text-align: center; margin: 32px 0;'>
                    <span style='font-size: 36px; font-weight: bold; letter-spacing: 8px;
                                 color: #4F46E5; background: #EEF2FF; padding: 16px 28px;
                                 border-radius: 8px;'>
                        {$code}
                    </span>
                </div>

                <p style='color: #888; font-size: 13px;'>
                    This code expires in 10 minutes. If you did not request this,
                    you can safely ignore this email.
                </p>
            </div>
        </body>
        </html>";
    }
}
