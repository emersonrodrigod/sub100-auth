<?php


namespace sub100\Auth;


use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class Authentication
{
    const CLIENT_TOKEN_URL = 'client-token';

    private string $clientId;

    private string $clientSecret;

    private string $authURL;

    public function __construct()
    {
        $this->clientId = config('sub100.client_id');
        $this->clientSecret = config('sub100.client_secret');
        $this->authURL = config('sub100.auth_url');
    }

    public function getToken()
    {
        $body = [
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
        ];

        $response = $this->getClient()->request('POST', self::CLIENT_TOKEN_URL, [
            'body' => json_encode($body),
        ]);

        $data = json_decode($response->getBody()->getContents());

        return [
            'access_token' => $data['access_token'],
            'expires_in' => $data['expires_in'],
        ];
    }

    private function getClient()
    {
        return new Client([
            'base_uri' => $this . $this->authURL,
            'headers' => [
                'content-type' => 'application/json',
                'Accept' => 'application/json'
            ]
        ]);
    }

    public function getAccessToken() {
        $accessToken = Cache::get('auth_access_token');

        if (!$accessToken) {
            try {
                $result = $this->getToken();
                Cache::put('auth_access_token', $result['access_token'], $result['expires_in']);
                return $result['access_token'];

            } catch (\Throwable $t) {
                Log::critical('Error getting access token from Auth service', [$t]);
                throw $t;
            }
        }

        return $accessToken;
    }

    public function clearAccessToken() {
        Cache::forget('auth_access_token');
    }
}
