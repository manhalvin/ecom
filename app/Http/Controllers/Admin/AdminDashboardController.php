<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminDashboardController extends Controller
{
    private $user;
    public function __construct()
    {
        $this->user = new User;
        $this->middleware(function ($request, $next) {
            session(['module_active' => 'dashboard']);
            return $next($request);
        });
    }

    public function index()
    {
        $users = $this->user->all();
        return view('admin.dashboard',compact('users'));
    }
}
