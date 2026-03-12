<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Services\LicenseService;

class CheckLicense
{
    protected $licenseService;

    public function __construct(LicenseService $licenseService)
    {
        $this->licenseService = $licenseService;
    }

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $exemptions = [
            'admin/login',
            'login',
            'logout',
            'admin/settings',
            'admin/license/reverify',
            'api/v1/license-kill',
        ];

        $path = $request->path();
        
        // Check exemptions
        foreach ($exemptions as $exemption) {
            if ($path === $exemption || str_starts_with($path, $exemption . '/')) {
                return $next($request);
            }
        }

        if (!$this->licenseService->isAccessible()) {
            return response()->view('errors.license', [
                'status' => $this->licenseService->getStatus()
            ], 403);
        }

        return $next($request);
    }
}
