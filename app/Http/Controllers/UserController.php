<?php

namespace App\Http\Controllers;

use App\Auth\DmUserProvider;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request) {
        $password = $request->get('password');
        if (!$request->has('isSha1')) {
            $password = sha1($password);
        }
        $dmUserProvider = new DmUserProvider();
        try {
            $username = $dmUserProvider->retrieveByCredentials([
                'username' => $request->get('username'),
                'password' => $password
            ]);
            $dmUserProvider->createSession($username, $password);
            return redirect('/');
        } catch (AuthenticationException $e) {
            session()->flash('error', $e->getMessage());
            return redirect('/login');
        }
    }

    public function logout() {
        $dmUserProvider = new DmUserProvider();
        $dmUserProvider->invalidateSession();

        session()->flash('message', 'You have been logged out');
        return redirect('/login');
    }
}
