<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WaitingApprovalController extends Controller
{
    public function index()
    {
        return view('waiting-approval');
    }
}
