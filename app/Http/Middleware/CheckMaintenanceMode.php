<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Superadmin;

class CheckMaintenanceMode
{
    public function handle(Request $request, Closure $next)
    {
        // Check if any superadmin has enabled maintenance mode
        $maintenanceMode = Superadmin::where('maintenance_mode', true)->exists();

        if ($maintenanceMode && !$request->is('maintenance') && !$request->is('superadmin/*')) {
            return redirect()->route('maintenance');
        }

        return $next($request);
    }
}
