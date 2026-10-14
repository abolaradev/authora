<?php

namespace Abolaradev\Authora\Services;

use Abolaradev\Authora\Enums\AuthenticationAction;
use Closure;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class AuthRateLimiter
{
    /**
     * The authentication action being rate-limited.
     */
    private string $action;

    /**
     * The key used to identify the rate limiter.
     */
    private string $key;

    /**
     * The number of seconds before the rate limit resets.
     */
    private int $decaySeconds;

    /**
     * The maximum number of allowed attempts.
     */
    private int $maxAttempts;

    /**
     * The number of seconds until the rate limit expires.
     */
    private int $availableIn;

    /**
     * The callback executed when the rate limit is exceeded.
     */    
    private ?Closure $onLimit;

    /**
     * The input field that receives the rate-limit validation error.
     */
    private string $input;

    /**
     * Create a new rate limiter instance.
     *
     * @param  AuthenticationAction  $action  The authentication action to limit.
     * @param  Closure|null  $onLimit  The optional callback executed when the limit is exceeded.
     */
    public function __construct(AuthenticationAction $action, ?Closure $onLimit)
    {
        $this->action = $action->value;
        $this->key = "authora-rate-limiter ({$this->action}): ".md5(request()->ip());
        $this->decaySeconds = config('authora.rate_limiter.decay_seconds');
        $this->maxAttempts = config('authora.rate_limiter.max_attempts');
        $this->availableIn = 0;
        $this->onLimit = $onLimit;
        $this->input = 'email';
    }

    /**
     * Set the input field that receives the rate-limit validation error.
     *
     * @param  string  $input  The input field name.
     * @return $this
     */
    public function errorOn(string $input)
    {
        $this->input = $input;

        return $this;
    }

    /**
     * Set a custom rate limiter key.
     *
     * @param  string  $key  The key used to track attempts.
     * @return static
     */
    public function key(string $key): static
    {
        $this->key = $key;

        return $this;
    }

    /**
     * Set the rate limit decay duration.
     *
     * @param  int  $decaySeconds  The number of seconds before the limit resets.
     * @return static
     */
    public function decaySeconds(int $decaySeconds): static
    {
        $this->decaySeconds = $decaySeconds;

        return $this;
    }

    /**
     * Set the maximum number of allowed attempts.
     *
     * @param  int  $maxAttempts  The maximum number of attempts before blocking.
     * @return static
     */
    public function maxAttempts(int $maxAttempts): static
    {
        $this->maxAttempts = $maxAttempts;

        return $this;
    }

    /**
     * Check the rate limit and record the current attempt if allowed.
     *
     * @return mixed The custom callback result or true when the attempt is allowed.
     *
     * @throws ValidationException When the rate limit is exceeded and a field name is configured.
     */
    public function execute()
    {
        if (RateLimiter::tooManyAttempts($this->key, $this->maxAttempts)) {
            $this->availableIn = RateLimiter::availableIn($this->key);

            return $this->tooManyAttempts();
        }

        RateLimiter::hit($this->key, $this->decaySeconds);

        return true;
    }

    /**
     * Get the number of seconds until the rate limit expires.
     */
    public function getAvailableIn(): int
    {
        return $this->availableIn;
    }

    /**
     * Handle a request that has exceeded the rate limit.
     *
     * @return mixed The callback result, if configured.
     *
     * @throws ValidationException When no callback is configured.
     */
    private function tooManyAttempts(): mixed
    {
        if ($this->onLimit instanceof Closure) {
            return ($this->onLimit)($this);
        }

        if(is_string($this->input)){
            throw ValidationException::withMessages([
                $this->input => __('auth.throttle', [
                    'seconds' => $this->getAvailableIn(),
                ]),
            ]);
        }
    }
}