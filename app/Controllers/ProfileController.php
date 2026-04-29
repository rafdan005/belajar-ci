<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class ProfileController extends BaseController
{
    public function index()
    {
        $data = [
            'username'   => session()->get('username'),
            'role'       => session()->get('role'),
            'email'      => session()->get('email'),
            'login_time' => session()->get('login_time'),
            'isLoggedIn' => session()->get('isLoggedIn'),
        ];

        return view('v_profile', $data);
    }
}