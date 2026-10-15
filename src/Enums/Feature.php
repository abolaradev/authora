<?php

namespace Abolaradev\Authora\Enums;

enum Feature: string
{
    /** Password-based user registration. */
    case Register = '\App\Actions\Authentication\Password\Register';

    /** Password-based user login. */
    case Login = '\App\Actions\Authentication\Password\Login';

    /** User logout. */
    case Logout = '\App\Actions\Logout\Logout';

    /** OTP-based authentication service. */
    case Otp = '\Abolaradev\Authora\Services\OtpBasedAuthentication';

    /** OAuth authentication. */
    case OAuth = '';

    /** Password reset service. */
    case ResetPassword = '\Abolaradev\Authora\Services\UserResetPassword';

    /** Email verification notification action. */
    case EmailVerification = '\App\Actions\Verification\SendEmailVerificationNotification';

    /**
     * Get the action classes used for OTP authentication.
     *
     * @return array<string, class-string>
     */
    public static function otpActions(): array
    {
        return [
            'dispatch' => '\App\Actions\Authentication\Otp\Dispatch',
            'verify' => '\App\Actions\Authentication\Otp\Verify',
        ];
    }

    /**
     * Get the action classes used for password reset.
     *
     * @return array<string, class-string>
     */
    public static function resetPasswordActions(): array
    {
        return [
            'send_link' => '\App\Actions\Password\SendResetPasswordLink',
            'save_password' => '\App\Actions\Password\SaveNewPassword',
        ];
    }
}