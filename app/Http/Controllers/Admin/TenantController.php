<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\TenantService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\TenantApprovalMail;

class TenantController extends Controller
{
    protected $tenantService;

    public function __construct(TenantService $tenantService)
    {
        $this->tenantService = $tenantService;
        $this->middleware(['auth', 'role:super_admin']);
    }

    public function index()
    {
        $pendingAdmins = User::where('role', 'admin')
            ->whereDoesntHave('tenant')
            ->get();

        $activeTenants = User::where('role', 'admin')
            ->whereHas('tenant', function ($query) {
                $query->where('is_active', true);
            })
            ->with('tenant')
            ->get();

        return view('admin.tenants.index', compact('pendingAdmins', 'activeTenants'));
    }

    public function approve(User $user)
    {
        if ($user->role !== 'admin' || $user->tenant) {
            return redirect()->back()->with('error', 'Invalid user or already approved.');
        }

        $tenant = $this->tenantService->createTenantDatabase($user);

        // Send email with credentials
        Mail::to($user->email)->send(new TenantApprovalMail($user, $tenant));

        return redirect()->back()->with('success', 'Tenant approved successfully.');
    }

    public function deactivate(User $user)
    {
        if (!$user->tenant) {
            return redirect()->back()->with('error', 'No tenant found for this user.');
        }

        $user->tenant->update(['is_active' => false]);

        return redirect()->back()->with('success', 'Tenant deactivated successfully.');
    }
}
