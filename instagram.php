<?php
require_once 'HttpClient.php';

class Instagram {
    const RESPONSE_CODE_PARAM = 'code';
    protected $_endpointUrls = array(
        'authorize' => 'https://api.instagram.com/oauth/authorize/?client_id=%s&redirect_uri=%s&response_type=%s',
        'access_token' => 'https://api.instagram.com/oauth/access_token',
        'user' => 'https://api.instagram.com/v1/users/%d/?access_token=%s',
    );

    protected $_config = array();
    protected $_arrayResponses = false;
    protected $_oauthToken = null;
    protected $_accessToken = null;
    protected $_currentUser = null;
    protected $_httpClient = null;

    public function __construct($config = null, $arrayResponses = false) {
        $this->_config = $config;
        $this->_arrayResponses = $arrayResponses;
        if (empty($config)) {
            throw new InstagramException('Configuration params are empty or not an array.');
        }
    }

    protected function _initHttpClient($uri, $method = HttpClient::GET) {
        if ($this->_httpClient == null) {
            $this->_httpClient = new HttpClient($uri);
        } else {
            $this->_httpClient->setUri($uri);
        }
        $this->_httpClient->setMethod($method);
    }

    protected function _getHttpClientResponse() {
        return $this->_httpClient->getResponse();
    }

    protected function _setOauthToken() {
        $this->_initHttpClient($this->_endpointUrls['access_token'], HttpClient::POST);
        $this->_httpClient->setPostParam('client_id', $this->_config['client_id']);
        $this->_httpClient->setPostParam('client_secret', $this->_config['client_secret']);
        $this->_httpClient->setPostParam('grant_type', $this->_config['grant_type']);
        $this->_httpClient->setPostParam('redirect_uri', $this->_config['redirect_uri']);
        $this->_httpClient->setPostParam('code', $this->getAccessCode());

        $this->_oauthToken = $this->_getHttpClientResponse();
    }

    public function getAccessToken() {
        if ($this->_accessToken == null) {
          
            if ($this->_oauthToken == null) {
                $this->_setOauthToken();
            }
          
            $this->_accessToken = json_decode($this->_oauthToken)->access_token;
        }

        return $this->_accessToken;
    }

    protected function getAccessCode() {
        return $_GET[self::RESPONSE_CODE_PARAM];
    }

    public function setAccessToken($accessToken) {
        $this->_accessToken = $accessToken;
    }
    
    public function openAuthorizationUrl() {
        $authorizationUrl = sprintf($this->_endpointUrls['authorize'],
            $this->_config['client_id'],
            $this->_config['redirect_uri'],
            self::RESPONSE_CODE_PARAM);

        header('Location: ' . $authorizationUrl);
        exit(1);
    }

    public function getUser($id) {
        $endpointUrl = sprintf($this->_endpointUrls['user'], $id, $this->getAccessToken());
        $this->_initHttpClient($endpointUrl);
        return $this->_getHttpClientResponse();
    }
}

class InstagramException extends Exception {
}
