<?php

namespace Obelaw\Obridge\Http\Middleware;

use Closure;
use Illuminate\Cache\RateLimiter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Obelaw\Obridge\Models\Obridge;
use Symfony\Component\HttpFoundation\Response;

class ObridgeMiddleware
{
    /**
     * The rate limiter instance.
     */
    protected RateLimiter $limiter;

    /**
     * Create a new middleware instance.
     */
    public function __construct(RateLimiter $limiter)
    {
        $this->limiter = $limiter;
    }

    /**
     * Handle an incoming request.
     *
     * @param Closure(Request):Response $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Extract and validate credentials
        $credentials = $this->extractCredentials($request);
        
        if (!$credentials) {
            $this->logAuthenticationAttempt($request, 'missing_credentials');
            return $this->unauthorizedResponse('Missing authentication headers');
        }

        // Apply rate limiting
        if ($this->isRateLimited($request, $credentials)) {
            $this->logAuthenticationAttempt($request, 'rate_limited', $credentials);
            return $this->rateLimitedResponse();
        }

        // Authenticate the request
        $obridge = $this->authenticate($credentials);
        
        if (!$obridge) {
            $this->incrementFailedAttempts($request, $credentials);
            $this->logAuthenticationAttempt($request, 'invalid_credentials', $credentials);
            return $this->unauthorizedResponse('Invalid credentials');
        }

        // Log successful authentication
        $this->logAuthenticationAttempt($request, 'success', $credentials, $obridge->id);
        
        // Add authenticated obridge to request
        $request->merge(['authenticated_obridge' => $obridge]);

        return $next($request);
    }

    /**
     * Extract authentication credentials from request headers.
     */
    protected function extractCredentials(Request $request): ?array
    {
        $name = $request->header('x-obridge-name');
        $secret = $request->header('x-obridge-secret');

        if (empty($name) || empty($secret)) {
            return null;
        }

        return [
            'name' => trim($name),
            'secret' => trim($secret)
        ];
    }

    /**
     * Authenticate the obridge using provided credentials.
     */
    protected function authenticate(array $credentials): ?Obridge
    {
        $cacheKey = 'obridge_auth:' . md5($credentials['name'] . ':' . $credentials['secret']);
        $cacheTtl = config('obridge.cache.ttl_minutes', 15);
        
        return Cache::remember($cacheKey, now()->addMinutes($cacheTtl), function () use ($credentials) {
            $obridge = Obridge::where('name', $credentials['name'])
                ->active()
                ->first();

            if (!$obridge || !$obridge->checkSecret($credentials['secret'])) {
                return null;
            }

            // Update last used timestamp (async to avoid blocking)
            $this->updateLastUsedAsync($obridge);

            return $obridge;
        });
    }

    /**
     * Update the last used timestamp asynchronously.
     */
    protected function updateLastUsedAsync(Obridge $obridge): void
    {
        // Use a simple database update to avoid model events
        DB::table($obridge->getTable())
            ->where('id', $obridge->id)
            ->update(['last_used_at' => now()]);
    }

    /**
     * Check if the request is rate limited.
     */
    protected function isRateLimited(Request $request, array $credentials): bool
    {
        $key = 'obridge_auth_attempts:' . $this->getRateLimitKey($request, $credentials);
        
        return $this->limiter->tooManyAttempts($key, $this->getMaxAttempts());
    }

    /**
     * Increment failed authentication attempts.
     */
    protected function incrementFailedAttempts(Request $request, array $credentials): void
    {
        $key = 'obridge_auth_attempts:' . $this->getRateLimitKey($request, $credentials);
        
        $this->limiter->hit($key, $this->getDecayMinutes() * 60);
    }

    /**
     * Get the rate limit key for the request.
     */
    protected function getRateLimitKey(Request $request, array $credentials): string
    {
        return sha1($request->ip() . '|' . $credentials['name']);
    }

    /**
     * Get the maximum number of attempts allowed.
     */
    protected function getMaxAttempts(): int
    {
        return config('obridge.rate_limit.max_attempts', 10);
    }

    /**
     * Get the number of minutes until rate limit resets.
     */
    protected function getDecayMinutes(): int
    {
        return config('obridge.rate_limit.decay_minutes', 60);
    }

    /**
     * Log authentication attempt.
     */
    protected function logAuthenticationAttempt(
        Request $request, 
        string $status, 
        ?array $credentials = null, 
        ?int $obridgeId = null
    ): void {
        if (!config('obridge.logging.log_attempts', true)) {
            return;
        }

        $logData = [
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'status' => $status,
            'timestamp' => now()->toISOString(),
        ];

        if ($credentials) {
            $logData['obridge_name'] = $credentials['name'];
        }

        if ($obridgeId) {
            $logData['obridge_id'] = $obridgeId;
        }

        $channel = config('obridge.logging.channel', 'obridge');
        Log::channel($channel)->info('Obridge authentication attempt', $logData);
    }

    /**
     * Return unauthorized response.
     */
    protected function unauthorizedResponse(string $message = 'Unauthorized'): Response
    {
        return response()->json([
            'error' => $message,
            'code' => 'UNAUTHORIZED',
            'timestamp' => now()->toISOString()
        ], 401);
    }

    /**
     * Return rate limited response.
     */
    protected function rateLimitedResponse(): Response
    {
        return response()->json([
            'error' => 'Too many authentication attempts. Please try again later.',
            'code' => 'RATE_LIMITED',
            'timestamp' => now()->toISOString()
        ], 429);
    }
}
