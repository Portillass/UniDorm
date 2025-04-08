<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function approve(User $user)
    {
        // Only super admins can approve admins
        if (!auth()->user()->isSuperAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        // Start a database transaction
        DB::beginTransaction();

        try {
            // Create a new tenant for the admin
            $tenant = Tenant::create([
                'name' => $user->name . "'s Tenant",
                'database' => 'tenant_' . Str::random(10),
                'is_active' => true,
            ]);

            // Associate the tenant with the admin
            $user->tenant()->associate($tenant);
            $user->save();

            // Commit the transaction
            DB::commit();

            return redirect()->route('dashboard')->with('success', 'Admin approved successfully!');
        } catch (\Exception $e) {
            // Rollback the transaction on error
            DB::rollBack();
            return redirect()->route('dashboard')->with('error', 'Failed to approve admin: ' . $e->getMessage());
        }
    }
}
