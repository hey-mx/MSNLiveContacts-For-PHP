<?php

class MsnLiveOAuth {
    
    private $clientId;
    private $clientSecret;

    const AUTH_URL = 'https://login.live.com/oauth20_authorize.srf';
    const AUTH_TOKEN_URL = 'https://login.live.com/oauth20_token.srf';
    const CONTACTS_SERVICE_URL = 'https://apis.live.net/v5.0/%s/%s';

    public function __construct($clientId, $clientSecret) {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
    }

    public function GetAccessCode($scope, $responseType, $csrfCode, $redirectUrl='') {
        $url = self::AUTH_URL .
                '?client_id='. $this->clientId .
                '&scope=' . rawurlencode($scope) .
                '&state=' . $csrfCode .
                '&response_type=' . $responseType .
                (!empty($redirectUrl) 
                    ? '&redirect_url=' . urlencode($redirectUrl) : '');
        header('Location: ' . $url);
    }

    public function ValidateCodeAndGetTokenAccess($code, $grantType='authorization_code') {
        $postData = array(
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'code' => $code,
            'grant_type' => $grantType,
        );
        $response = $this->CallPostBack(self::AUTH_TOKEN_URL, $postData);
        return json_decode($response, true); 
    }

    public function GetContacts($accessToken, $userId='me') {
        $contacts = file_get_contents(sprintf(self::CONTACTS_SERVICE_URL, 
            $userId, 'contacts') . '?access_token=' . $accessToken);
        return json_decode($contacts, true);
    }

    private function CallPostBack($url, $postData) {
        $data = http_build_query($postData);
        $opts = array(
            'http' => array(
                'method' => 'POST',
                'header' => 'Content-type: application/x-www-form-urlencoded;charset=UTF-8',
                'content' => $data,
            )
        );
        $context = stream_context_create($opts);
        $result = file_get_contents($url, false, $context);
        return $result;
    }
}
