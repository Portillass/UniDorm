<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class SetTenantDatabase
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->isAdmin()) {
            $tenant = auth()->user()->tenant;
            
            if ($tenant && $tenant->is_active) {
                Config::set('database.connections.tenant.database', $tenant->db_name);
                Config::set('database.connections.tenant.username', $tenant->db_user);
                Config::set('database.connections.tenant.password', $tenant->db_password);
                Config::set('database.connections.tenant.host', $tenant->db_host);
                
                DB::purge('tenant');
                DB::reconnect('tenant');
            }
        }

        return $next($request);
    }
}
