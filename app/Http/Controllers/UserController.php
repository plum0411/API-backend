<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class UserController
{
    public function getUser()
    {
        $user = Auth::user();
        return response()->json([
            'id' => $user->id,
        ]);
    }
}
