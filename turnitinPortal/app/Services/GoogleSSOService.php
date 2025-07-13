<?php

namespace App\Services;

use Google_Client;
use Google_Service_Oauth2;

class GoogleSSOService
{
    protected $client;

    public function __construct()
    {
        // Load biến môi trường
        if (!isset($_ENV['GOOGLE_CLIENT_ID'])) {
            $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
            $dotenv->load();
        }

        $this->client = new Google_Client();
        $this->client->setClientId($_ENV['GOOGLE_CLIENT_ID']);
        $this->client->setClientSecret($_ENV['GOOGLE_CLIENT_SECRET']);
        $this->client->setRedirectUri($_ENV['GOOGLE_REDIRECT_URI']);
        $this->client->addScope('email');
        $this->client->addScope('profile');
    }

    public function getLoginUrl()
    {
        return $this->client->createAuthUrl();
    }

    public function handleCallback()
    {
        if (!isset($_GET['code'])) return null;

        $this->client->authenticate($_GET['code']);
        $token = $this->client->getAccessToken();

        if ($token) {
            $this->client->setAccessToken($token);
            $oauth2 = new Google_Service_Oauth2($this->client);
            $userinfo = $oauth2->userinfo->get();

            return [
                'email' => $userinfo->email,
                'name'  => $userinfo->name
            ];
        }

        return null;
    }
}
