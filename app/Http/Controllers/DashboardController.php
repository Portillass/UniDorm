<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if ($user->isSuperAdmin()) {
            $pendingAdmins = User::where('role', 'admin')
                ->whereDoesntHave('tenant')
                ->get();
                
            return view('dashboard', compact('pendingAdmins'));
        } elseif ($user->isAdmin()) {
            $students = User::where('role', 'student')->get();
            $totalStudents = $students->count();
            
            return view('dashboard', compact('students', 'totalStudents'));
        } else {
            // Student dashboard
            $studentRecords = $user->records;
            return view('dashboard', compact('studentRecords'));
        }
    }
}
