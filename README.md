# Authora

**Authora** is a Laravel authentication package designed to simplify the implementation of authentication features in your application. It provides a flexible, configurable, and extensible approach to managing authentication without requiring a predefined user interface.

## Features

- Password-based authentication
- OTP-based authentication
- OAuth authentication
- Password reset
- Email verification
- Configurable authentication features
- Authentication rate limiting
- Customizable authentication actions

## Installation

Install Authora via Composer:

```bash
composer require abolaradev/authora
```

### Publish Configuration

Publish the configuration file:

```bash
php artisan vendor:publish --tag='authora-config'
```

### Publish Language Files

Publish the language files:

```bash
php artisan vendor:publish --tag='authora-lang'
```

### Publish Migrations

Publish the package migrations and run them:

```bash
php artisan vendor:publish --tag='authora-migrations'

php artisan migrate
```

### Install Authentication Actions

Run the following command to install Authora's authentication actions into your application:

```bash
php artisan authora:install
```

# 1. Authentication

## Credentials

The `credentials()` method accepts an associative array of data used by Authora's authentication operations. The provided data is stored and passed to the corresponding action when an operation is executed.

```php
use Abolaradev\Authora\Facades\Authora;

Authora::credentials([
    'email' => 'john@example.com',
    'password' => 'secret-password',
]);
```

The required fields depend on the operation being performed. You can find the required credentials for each operation in its corresponding section below.

## Password-Based Authentication

### Registration

Use the `register()` method to register a new user.

```php
Authora::credentials([
    'name' => 'John Doe',
    'email' => 'john@example.com',
    'password' => 'secret-password',
])->register();
```

Authora executes the configured registration action to validate the provided data and register the user.

### Login

Use the `login()` method to authenticate a user using their credentials.

```php
Authora::credentials([
    'email' => 'john@example.com',
    'password' => 'secret-password',
])->login();
```

Authora executes the configured login action to validate the credentials and authenticate the user.

### Logout

Use the `logout()` method to log out the currently authenticated user.

```php
Authora::logout();
```

Authora executes the configured logout action to handle the logout process.

## OTP-Based Authentication

Authora supports OTP-based authentication through the [`abolaradev/otp`](https://github.com/abolaradev/otp) package, which provides OTP generation, delivery, and verification functionality.

Authora integrates this package into its authentication workflow through two operations: `dispatch()` and `verify()`.

### Dispatch OTP

Use `otp()->dispatch()` to initiate the OTP dispatch process.

```php
Authora::credentials([
    'phone' => '09123456789',
])->otp()->dispatch();
```

### Verify OTP

Use `otp()->verify()` to verify the submitted OTP.

```php
Authora::credentials([
    'phone' => '09123456789',
    'token' => '123456',
])->otp()->verify();
```

The required credentials depend on the operation being performed.

For detailed documentation on OTP configuration, notification channels, code dispatch, and verification, refer to the [`abolaradev/otp` repository](https://github.com/abolaradev/otp).

## OAuth Authentication

Authora supports OAuth authentication through [Laravel Socialite](https://laravel.com/framework/docs/socialite), allowing users to authenticate using external providers such as Google.

### Usage

Use the `oauth()` method to initiate OAuth authentication. The method accepts the provider name as its argument.

```php
Authora::oauth('google');
```

The provider name must match a provider registered in the Authora configuration.

### Configuration

OAuth providers and their routes can be configured in `config/authora.php` under `options.oauth`:

```php
'oauth' => [
    'providers' => [
        // 'google' => \App\Actions\Authentication\OAuth\Google::class,
    ],

    'routes' => [
        'redirect' => '/auth/{provider}/redirect',
        'callback' => '/auth/{provider}/callback',
    ],
],
```

- **`providers`**: Maps each provider name to its corresponding action class.
- **`routes.redirect`**: Defines the route used to redirect users to the OAuth provider.
- **`routes.callback`**: Defines the route that handles the response from the OAuth provider.

### Default Google Provider

Authora includes a default Google OAuth action at:

```text
app/Actions/OAuth/Google.php
```

You can customize this action to meet your application's requirements, just like other Authora actions.

### Adding a Custom Provider

To add a new OAuth provider, create its corresponding action class and register it in the `providers` array. No additional Authora-specific configuration is required beyond registering the class and configuring the provider credentials required by Socialite.

For example, to add GitHub:

```php
'providers' => [
    'google' => \App\Actions\OAuth\Google::class,
    'github' => \App\Actions\OAuth\GitHub::class,
],
```

For more information about OAuth providers and their configuration, see the [Laravel Socialite documentation](https://laravel.com/framework/docs/socialite).

# 2. Email Verification

Authora provides email verification functionality using Laravel's built-in email verification system. The required routes and configuration are already set up, so you can send verification emails without additional setup.

### Implement the Email Verification Interface

To enable email verification for your users, your `User` model must implement Laravel's `MustVerifyEmail` interface.

Add the interface to your model:

```php
use Illuminate\Contracts\Auth\MustVerifyEmail;

class User extends Authenticatable implements MustVerifyEmail
{
    // ...
}
```

Laravel's default `Authenticatable` model provides the email verification methods required by this interface. If your model extends a different base class, make sure those methods are implemented.

### Send Verification Email

Use the `sendEmailVerificationNotification()` method to send an email verification notification to the authenticated user.

```php
Authora::sendEmailVerificationNotification();
```

### Configuration

You can customize the email verification routes and the notice view in `config/authora.php` under `options.email_verification`:

```php
'email_verification' => [

    'notice_view' => null,

    'routes' => [
        'notice' => '/email/verify',
        'verify' => '/email/verify/{id}/{hash}',
    ],

],
```

- **`notice_view`**: Defines the view displayed when a user needs to verify their email address. Set it to a view name to use a custom notice page.
- **`routes.notice`**: Defines the route for the email verification notice page.
- **`routes.verify`**: Defines the route used to verify the user's email address.

### Customize the Verification Email

You can customize the verification email using Laravel's `VerifyEmail::toMailUsing()` method in your application's `AppServiceProvider`.

```php
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

public function boot(): void
{
    // ...

    VerifyEmail::toMailUsing(function (object $notifiable, string $url) {
        return (new MailMessage)
            ->subject('Verify Email Address')
            ->line('Click the button below to verify your email address.')
            ->action('Verify Email Address', $url);
    });
}
```

This allows you to customize the email subject, message, action button, and other elements of the verification notification.

For more information about email verification, refer to the [Laravel Email Verification documentation](https://laravel.com/framework/docs/verification).

Authora configures the verification workflow for you. You can customize the routes, notice view, and email notification whenever your application requires it.

# 3. Password Reset

Authora provides a password reset workflow that allows users to request a password reset link and save a new password. The required configuration is already in place.

### Implement the Password Reset Interface

Your `User` model must support Laravel's `CanResetPassword` contract and its corresponding functionality.

If your model extends Laravel's standard `Illuminate\Foundation\Auth\User` class, this support is already provided. For a custom model that does not inherit this functionality, you can implement the contract and use Laravel's trait:

```php
use Illuminate\Auth\Passwords\CanResetPassword as CanResetPasswordTrait;
use Illuminate\Contracts\Auth\CanResetPassword;

class User extends Model implements CanResetPassword
{
    use CanResetPasswordTrait;

    // ...
}
```

The trait provides the methods required by Laravel's password reset workflow. If your model already inherits this functionality, you do not need to add the interface and trait a second time.

### Send a Password Reset Link

Use `passwordReset()->sendResetLink()` to send a password reset link to the user's email address.

```php
Authora::credentials([
    'email' => 'user@example.com',
])
    ->passwordReset()
    ->sendResetLink();
```

### Save a New Password

Use `passwordReset()->saveNewPassword()` to reset the user's password using the token received in the reset link.

```php
Authora::credentials([
    'token' => $token,
    'email' => 'user@example.com',
    'password' => 'new-secure-password',
    'password_confirmation' => 'new-secure-password',
])
    ->passwordReset()
    ->saveNewPassword();
```

The credentials array must contain the values submitted by your password reset form.

### Define the Password Reset Route

Although Authora's password reset configuration is already set up, your application must define a route named `password.reset`. Laravel uses this route name to generate password reset URLs.

For example, in `routes/web.php`:

```php
use Illuminate\Support\Facades\Route;

Route::get('/reset-password/{token}', function (string $token) {
    return view('auth.reset-password', ['token' => $token]);
})->name('password.reset');
```

Replace the view with your application's password reset form. Make sure the form receives the token and the user's email address.

### Customize the Password Reset Email

You can customize the password reset email by overriding `sendPasswordResetNotification()` in your `App\Models\User` model.

First, import your custom notification:

```php
use App\Notifications\ResetPasswordNotification;
```

Then add the following method to your `User` model:

```php
/**
 * Send a password reset notification to the user.
 *
 * @param string $token
 */
public function sendPasswordResetNotification($token): void
{
    $url = 'https://example.com/reset-password?token='.$token;

    $this->notify(new ResetPasswordNotification($url));
}
```

Replace the example URL with the appropriate URL for your application and customize `ResetPasswordNotification` to control the email's content and layout.

For more information about Laravel's password reset functionality, see the [Laravel Password Reset documentation](https://laravel.com/framework/docs/passwords).

# Customizing Actions

Authora allows you to customize its authentication actions to match your application's requirements. You can modify the default implementations, change validation rules, customize authentication logic, adjust rate limiting, and control redirect behavior.

To install the authentication actions into your application, run:

```bash
php artisan authora:install
```

The actions will be copied into your application's `app/Actions` directory. You can then modify them directly without changing the package source code.

## Example: Customizing the Login Action

The following example demonstrates how Authora handles password authentication through a customizable `Login` action.

```php
class Login implements ShouldAuthenticate
{
    use WithValidation, HandlesAuthRedirects;

    /**
     * Get the validation rules for password authentication.
     *
     * @return array
     */
    protected function rules(): array
    {
        return [
            'email' => Email::rules(),
            'password' => 'required',
            'remember' => 'boolean',
        ];
    }

    /**
     * Authenticate the user using their email and password.
     *
     * @throws ValidationException
     */
    public function authenticate()
    {
        $execute = Authora::rateLimiter(AuthenticationAction::Login)
            ->execute();

        if ($execute) {
            $validated = $this->getValidatedData();

            $user = User::where('email', '=', $validated['email'])->first();

            if (! $user || ! Hash::check($validated['password'], $user->password)) {
                throw ValidationException::withMessages([
                    'email' => __('auth.failed'),
                ]);
            }

            Auth::login($user, $validated['remember'] ?? false);

            Session::regenerate();

            return $this->redirectToDashboard();
        }
    }
}
```

This action uses the `WithValidation` trait to validate the input, `Authora::rateLimiter()` to control authentication attempts, and the `HandlesAuthRedirects` trait to manage redirect behavior after successful authentication.

## Validation

The `WithValidation` trait provides validation functionality for authentication actions. Define the validation rules in the action's `rules()` method and retrieve the validated data using `getValidatedData()`.

For example:

```php
protected function rules(): array
{
    return [
        'email' => Email::rules(),
        'password' => 'required',
        'remember' => 'boolean',
    ];
}
```

You can customize these rules to match your application's requirements, including adding new fields, changing validation constraints, or introducing custom validation rules.

## Rate Limiting

Authora provides rate-limiting functionality through the `AuthRateLimiter` trait. Each authentication action can have its own rate-limiting configuration, allowing you to customize the rate-limit key, decay period, and maximum number of attempts.

To configure rate limiting for a specific action, use `Authora::rateLimiter()` and pass the corresponding case from the `AuthenticationAction` enum.

```php
use Abolaradev\Authora\Enums\AuthenticationAction;

Authora::rateLimiter(AuthenticationAction::Login)
    ->key('Your-key')
    ->decaySeconds(1800)
    ->maxAttempts(3)
    ->execute();
```

The `AuthenticationAction` enum identifies which authentication action the rate limiter applies to.

Available configuration methods:

- **`key()`**: Sets the key used to identify the rate-limited operation.
- **`decaySeconds()`**: Defines the duration, in seconds, before the rate-limit counter resets.
- **`maxAttempts()`**: Sets the maximum number of attempts allowed during the decay period.
- **`execute()`**: Executes the rate-limiting check.

You can also configure the default rate-limiting settings in `config/authora.php`:

```php
'rate_limiter' => [
    'max_attempts' => 50,
    'decay_seconds' => 3600,
],
```

- **`max_attempts`**: The default maximum number of attempts.
- **`decay_seconds`**: The default duration, in seconds, before the rate-limit counter resets.

These defaults can be adjusted to suit your application's security requirements, while individual actions can use their own settings through the methods shown above.

Rate limiting is executed after input validation. As a result, requests that fail validation do not count toward the rate limit. However, valid requests that fail during the authentication process are counted as attempts.

## Redirects

Authora provides the `HandlesAuthRedirects` trait to manage redirect behavior within authentication actions.

The default redirect destinations are configurable in `config/authora.php`:

```php
'redirect_to' => [
    'login' => '/login',
    'dashboard' => '/dashboard',
],
```

- **`login`**: Defines the login page URL. used by  `redirectToLogin()`.
- **`dashboard`**: Defines the dashboard destination used when an action calls `redirectToDashboard()`.

You can call redirectToLogin() or redirectToDashboard() in your authentication actions to redirect users to the corresponding destinations. You can also modify these destinations in the configuration file or customize the action's redirect logic to implement your own behavior.

## Customize Other Actions

The same approach applies to other installed actions, including registration, logout, OTP authentication, OAuth, and password reset.

Run `php artisan authora:install` to install the actions, then modify the relevant classes according to your application's requirements.

---

If you find this package useful, please don't forget to give it a ⭐ on GitHub. ❤️

## Changelog

Please see [CHANGELOG.md](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING.md](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) for information on how to report security vulnerabilities.

## Credits

- [abolaradev](https://github.com/abolaradev)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [LICENSE.md](LICENSE.md) for more information.
