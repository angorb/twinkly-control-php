<?php

namespace Angorb\TwinklyControl;

class Request
{
    public const OK = 1000;
    public const ERROR = 1102;

    public const NETWORK_MODE_STATION = 1;
    public const NETWORK_MODE_AP = 2;

    public const MODE_OFF = 'off';
    public const MODE_DEMO = 'demo';
    public const MODE_MOVIE = 'movie';
    public const MODE_REALTIME = 'rt';

    private static $statusCodes = [
        1000 => 'OK',
        1101 => 'Error: Invalid Parameter Value',
        1102 => 'Error',
        1103 => 'Error: Value Too Long',
        1104 => 'Error: Invalid JSON',
        1105 => 'Error: Invalid Parameter Key',
        1107 => 'OK',
        1108 => 'OK',
    ];

    private $guzzleClient;
    private $authentication = [];

    /**
     * @param string $ip
     * @param null|string $challenge
     * @return void
     */
    public function __construct(string $ip, ?string $challenge = null)
    {
        $ip = \filter_var($ip, \FILTER_VALIDATE_IP);
        if (empty($ip)) {
            throw new \Angorb\TwinklyControl\Exception\InvalidAddressException($ip);
        }

        // create a new Guzzle client instance for HTTP requests
        $this->guzzleClient = new \GuzzleHttp\Client([
            'base_uri' => "http://{$ip}/xled/v1/",
        ]);

        // authenticate and store token
        $this->login();

        // verify Auth token to prepare for first request
        $this->verify();
    }

    /**
     * @param null|string $challenge
     * @return mixed
     */
    private function login(?string $challenge = null)
    {
        // if no challenge string is provided, create a random one
        if (empty($challenge)) {
            $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $challenge = \str_shuffle($chars);
        }

        $response = $this->makeRequest('login', [
            'challenge' => \base64_encode($challenge),
        ], \false);

        if (empty($response)) {
            return \false;
        }

        $this->authentication = [
            'token' => $response['authentication_token'],
            'expires_at' => \time() + $response['authentication_token_expires_in'],
            'response' => $response['challenge-response'],
        ];

        return \true;
    }

    /** @return int  */
    public function logout()
    {
        $result = $this->makeRequest('logout');
        return $result['code'] ?? self::ERROR;
    }

    /** @return bool  */
    public function verify(): bool
    {
        $response = $this->makeRequest('verify', [
            'challenge-response' => $this->authentication['response'],
        ]);

        if ($response['code'] === self::OK) {
            return \true;
        }

        return \false;
    }

    /** @return mixed  */
    public function getDeviceDetails()
    {
        return $this->makeRequest('gestalt', \null, \false);
    }

    /** @return mixed  */
    public function getFirmwareVersion()
    {
        return $this->makeRequest('fw/version', \null, \false);
    }

    /** @return mixed  */
    public function getDeviceName()
    {
        return $this->makeRequest('device_name');
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function setDeviceName(string $name)
    {
        $name = \substr($name, 0, 32); // avoid generating an error
        return $this->makeRequest('device_name', ['name' => $name]);
    }

    /** @return mixed  */
    public function getTimer()
    {
        return $this->makeRequest('timer');
    }

    /**
     * @param int $timeOn
     * @param int $timeOff
     * @return mixed
     */
    public function setTimer(int $timeOn, int $timeOff)
    {
        return $this->makeRequest('timer', [
            'time_now' => \Angorb\TwinklyControl\Timer::now(),
            'time_on' => \Angorb\TwinklyControl\Timer::getTime($timeOn),
            'time_off' => \Angorb\TwinklyControl\Timer::getTime($timeOff),
        ]);
    }

    public function disableTimer()
    {
        return $this->makeRequest('timer', [
            'time_now' => \Angorb\TwinklyControl\Timer::now(),
            'time_on' => -1,
            'time_off' => -1,
        ]);
    }

    /**
     * @param string $mode
     * @return mixed
     */
    public function setMode(string $mode)
    {
        // TODO input validation
        return $this->makeRequest('led/mode', ['mode' => $mode]);
    }

    /** @return mixed  */
    public function getBrightness()
    {
        return $this->makeRequest('led/out/brightness');
    }

    /**
     * @param int $level
     * @return mixed
     */
    public function setBrightness(int $level = 100)
    {
        $requestData = [
            'mode' => 'enabled',
            'value' => $level,
        ];

        if ($level >= 100) {
            $requestData = ['mode' => 'disabled'];
        }
        return $this->makeRequest('led/out/brightness', $requestData);
    }

    /**
     * @param string $endpoint
     * @param null|array $data
     * @param bool $authenticated
     * @return mixed
     */
    private function makeRequest(string $endpoint, ?array $data = null, bool $authenticated = \true)
    {
        $method = 'get';

        if (!empty($data)) {
            $method = 'post';
            $requestBody = [
                'json' => $data,
            ];
        }

        if ($authenticated) {
            $requestBody = ($requestBody ?? []) + ['headers' => $this->getAuthHeaders()];
        }

        $response = $this->guzzleClient->$method($endpoint, $requestBody ?? []);
        return \json_decode($response->getBody()->getContents(), \true);
    }

    /** @return array  */
    private function getAuthHeaders()
    {
        return ['X-Auth-Token' => $this->authentication['token']];
    }
}
