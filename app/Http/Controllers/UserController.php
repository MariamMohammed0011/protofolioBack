<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Resources\UserResource;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('projects')->get();
        return UserResource::collection($users);
    }
    public function show($id)
{
    $user = User::with('projects')->findOrFail($id); 
    return new UserResource($user); 
}
}