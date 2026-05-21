<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Loading extends BaseController
{
    public function index()
    {
        // Check if user is logged in
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        return view('loading/index');
    }
}