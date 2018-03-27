<?php

namespace App\Http\Controllers;

use App\Auth\DmUserProvider;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function login(Request $request) {
        $pass = $request->get('pass');
        if (!$request->has('isSha1')) {
            $pass = sha1($pass);
        }
        $dmUserProvider = new DmUserProvider();
//        try {
            $user = $dmUserProvider->retrieveByCredentials([
                'username' => $request->get('user'),
                'password' => $pass
            ]);
            $dmUserProvider->createSession($user, $pass);
            return JsonResponse::create([
                'user' => $user->getAuthIdentifierName()
            ]);

    }

    public function logout() {
        $dmUserProvider = new DmUserProvider();
        $dmUserProvider->invalidateSession();
        return JsonResponse::create([
            'user' => session()->get('username')
        ]);
    }
}
