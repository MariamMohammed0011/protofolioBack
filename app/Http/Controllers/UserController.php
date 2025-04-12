<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Cache;

class UserController extends Controller
{
    public function index()
    {
        // نخزن البيانات في الكاش لمدة 60 دقيقة
        $users = Cache::remember('users_with_projects', 60, function () {
            return User::with('projects')->get();
        });

        return UserResource::collection($users);
    }

    public function show($id)
    {
        // كاش لكل مستخدم على حدة لتوفير أسرع استجابة
        $cacheKey = 'user_with_projects_' . $id;

        $user = Cache::remember($cacheKey, 60, function () use ($id) {
            return User::with('projects')->findOrFail($id);
        });

        return new UserResource($user);
    }
}
