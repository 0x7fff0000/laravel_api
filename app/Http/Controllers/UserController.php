<?php

namespace App\Http\Controllers;

use Exception;

use Illuminate\Http\Request;

use App\Models\User;

use App\Http\Resources\UserResource;
use App\Http\Resources\UserCollection;

class UserController extends Controller
{
    public function index(Request $request)
    {
        return $request->user();
    }

    public function list()
    {
        return UserResource::collection(User::all());
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return new UserResource($user);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:64|min:5',
            'email' => 'required|email',
            'password' => 'required|max:32|min:10'
        ]);

        $user = User::create($request->all());
        $user->hashPassword($user->password);
        $user->save();
        return new UserResource($user);
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'max:64|min:5',
            'email' => 'email',
            'password' => 'max:32|min:10'
        ]);
        try {
            $newData = $request->only('name', 'email');
            $user = $request->user();
            $user->update($newData);
            $user->hashPassword($request->get('password'));
            $user->save();
            return new UserResource($user);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ])->setStatusCode(500);
        }
    }
}
