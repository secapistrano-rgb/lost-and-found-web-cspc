<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use Google_Client;

class Login extends BaseController
{
    protected $googleClient;

    public function __construct()
    {
        $this->googleClient = new Google_Client();
                $this->googleClient->setClientId($_ENV['GOOGLE_CLIENT_ID']);
                $this->googleClient->setClientSecret($_ENV['GOOGLE_CLIENT_SECRET']);
        $this->googleClient->setRedirectUri(base_url('login/callback'));
        $this->googleClient->addScope('email');
        $this->googleClient->addScope('profile');
    }

    public function index()
    {
        // If already logged in, redirect to dashboard
        if (session()->get('logged_in')) {
            return redirect()->to('/dashboard');
        }

        $data['login_url'] = $this->googleClient->createAuthUrl();
        return view('login/index', $data);
    }

    public function auth()
    {
        return redirect()->to($this->googleClient->createAuthUrl());
    }

    public function callback()
    {
        $token = $this->googleClient->fetchAccessTokenWithAuthCode($this->request->getVar('code'));

        if (isset($token['error'])) {
            // Handle error if token has error
            return redirect()->to('/login')->with('error', $token['error_description'] ?? 'Failed to retrieve access token.');
        }

        // Store access token in session for logout
        session()->set('access_token', $token['access_token']);

        $this->googleClient->setAccessToken($token['access_token']);
        $googleService = new \Google_Service_Oauth2($this->googleClient);
        $data = $googleService->userinfo->get();

        // Check if the email domain is from CSPC
        if (isset($data['email']) && str_ends_with($data['email'], '@my.cspc.edu.ph')) {
            $sessionData = [
                'name' => $data['name'],
                'email' => $data['email'],
                'profile_pic' => $data['picture'],
                'logged_in' => true,
            ];
            session()->set($sessionData);
            return redirect()->to('/dashboard');
        } else {
            // If email is not valid, revoke token immediately and show error
            $this->googleClient->revokeToken(session()->get('access_token'));
            session()->setFlashdata('error', 'There was a problem signing in. Please try again with your @my.cspc.edu.ph account.');
            return redirect()->to('/login');
        }
    }

    public function logout()
    {
        if (session()->has('access_token')) {
            $this->googleClient->revokeToken(session()->get('access_token'));
        }
        session()->destroy();
        return redirect()->to('/login');
    }
}
