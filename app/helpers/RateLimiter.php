<?php
class RateLimiter
{
    private $key;
    private $maxAttempts;
    private $decaySeconds;

    public function __construct($key, $maxAttempts = 5, $decaySeconds = 60)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $this->key = 'ratelimit_' . md5($key);
        $this->maxAttempts = $maxAttempts;
        $this->decaySeconds = $decaySeconds;

        if (!isset($_SESSION[$this->key])) {
            $_SESSION[$this->key] = [
                'attempts' => 0,
                'expires_at' => time() + $this->decaySeconds
            ];
        }

        // Reset after expiry
        if (time() > $_SESSION[$this->key]['expires_at']) {
            $_SESSION[$this->key] = [
                'attempts' => 0,
                'expires_at' => time() + $this->decaySeconds
            ];
        }
    }

    public function hit()
    {
        $_SESSION[$this->key]['attempts']++;
    }

    public function tooManyAttempts()
    {
        return $_SESSION[$this->key]['attempts'] >= $this->maxAttempts;
    }

    public function remaining()
    {
        return $this->maxAttempts - $_SESSION[$this->key]['attempts'];
    }

    public function availableIn()
    {
        return $_SESSION[$this->key]['expires_at'] - time();
    }
}
