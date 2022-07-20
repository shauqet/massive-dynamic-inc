<?php

namespace App\Http\Controllers;


use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index() {
        return view("layouts.cms");
    }
    public function getAdminInfo() {
        $user = User::with('contactPersons')->find(Auth::id());
        return response()->json(["success", $user], 200);
    }
}
