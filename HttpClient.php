<?php

class HttpClient {

    const GET = 'GET';
    const POST = 'POST';
    const DELETE = 'DELETE';

    private $handler;
    private $postParams = array();

    public function __construct($uri) {
        $this->handler = curl_init($uri);
        $this->_setOptions();
    }

    protected function _setOptions() {
        curl_setopt($this->handler, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->handler, CURLOPT_FOLLOWLOCATION, true);
    }

    public function setUri($uri) {
        $this->handler = curl_init($uri);
        $this->_setOptions();
    }

    public function setMethod($method = self::GET) {
        switch ($method) {
            case self::GET :
                curl_setopt($this->handler, CURLOPT_HTTPGET, true);
                break;
            case self::POST :
                curl_setopt($this->handler, CURLOPT_POST, true);
                break;
            case self::DELETE :
                curl_setopt($this->handler, CURLOPT_CUSTOMREQUEST, self::DELETE);
                break;
            default:
                throw new CurlHttpClientException('Method not supported');
        }
    }

    public function setPostParam($name, $value) {
        $this->postParams[$name] = $value;
        curl_setopt($this->handler, CURLOPT_POSTFIELDS, $this->postParams);
    }

    public function getResponse() {
        $response = curl_exec($this->handler);
        curl_close($this->handler);

        return $response;
    }

}
