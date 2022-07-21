<?php

namespace App\Http\Controllers;


use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class AdminController extends Controller
{
    public function index() {
        return view("layouts.cms");
    }
    public function getAdminInfo() {
        $user = User::with(['contactPersons','documents'])->find(Auth::id());
        return response()->json(["success", $user], ResponseAlias::HTTP_OK);
    }
}
