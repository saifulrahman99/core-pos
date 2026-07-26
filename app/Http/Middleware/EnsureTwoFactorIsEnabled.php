<?php

namespace App\Http\Middleware;

use App\Services\TwoFactorService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTwoFactorIsEnabled
{
    public function __construct(
        private readonly TwoFactorService $twoFactorService,
    ) {}

    /**
     * Handle an incoming request.
     *
     * If the user has a role that requires mandatory two-factor authentication
     * but has not enabled it yet, redirect them to the security settings page.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $this->twoFactorService->isRequired($user) && ! $this->twoFactorService->isEnabled($user)) {
            return redirect()->route('security.edit')->with('flash', [
                'type' => 'warning',
                'message' => 'Two-factor authentication is required for your role. Please set it up to continue.',
            ]);
        }

        return $next($request);
    }
}
