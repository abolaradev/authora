<?php

namespace Abolaradev\Authora\Enums;

enum AuthenticationAction: string
{
    // Password authentication
    case Register = 'register';
    case Login = 'login';
    case Logout = 'logout';

    // OTP authentication
    case SendOtp = 'send_otp';
    case VerifyOtp = 'verify_otp';

    // OAuth authentication
    case OAuthRedirect = 'oauth_redirect';
    case OAuthCallback = 'oauth_callback';

    // Password reset
    case SendPasswordResetLink = 'send_password_reset_link';
    case ResetPassword = 'reset_password';

    // Email verification
    case SendEmailVerification = 'send_email_verification';
    case VerifyEmail = 'verify_email';
}