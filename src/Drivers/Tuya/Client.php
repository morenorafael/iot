<?php

namespace Morenorafael\Iot\Drivers\Tuya;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class Client
{
    protected $endpoint = '';

    protected $accessId = '';

    protected $accessSecret = '';

    protected $httpClient;

    const KEY_TU_YA_ACCESS_TOKEN = 'tuya:access_token';

    const KEY_TU_YA_TOKEN_RES = 'tuya:token_res';

    public function getClient($config)
    {
        $this->endpoint = $config['url'];
        $this->accessId = $config['client_id'];
        $this->accessSecret = $config['access_token'];
        $this->httpClient = new \GuzzleHttp\Client(['base_uri' => $this->endpoint]);

        if (! Cache::has(self::KEY_TU_YA_ACCESS_TOKEN)) {
            if (Cache::has(self::KEY_TU_YA_TOKEN_RES)) {
                $refreshToken = Cache::get(self::KEY_TU_YA_TOKEN_RES)['refresh_token'];
                $tokenRes = $this->getToken($refreshToken);
                if (! $tokenRes['success']) {
                    $tokenRes = $this->getToken();
                }
            } else {
                $tokenRes = $this->getToken();
            }

            if ($tokenRes['success']) {
                Cache::put(self::KEY_TU_YA_ACCESS_TOKEN, $tokenRes['result']['access_token'], $tokenRes['result']['expire_time']);
                Cache::put(self::KEY_TU_YA_TOKEN_RES, $tokenRes['result'], $tokenRes['result']['expire_time'] + 3600);
            }
        }

        return $this;
    }

    public function send(string $method, string $url, array $options = [])
    {
        $method = strtoupper($method);

        if (! empty($options['params'])) {
            foreach ($options['params'] as $k => $v) {
                $url = str_replace('{'.$k.'}', $v, $url);
            }
        }
        if (! empty($options['query'])) {
            ksort($options['query']);
            $url .= '?'.http_build_query($options['query']);
        }

        $requestOptions = [
            'headers' => $this->getHeaders($method, $url, isset($options['body']) ? json_encode($options['body']) : ''),
        ];

        if (isset($options['body'])) {
            $requestOptions['json'] = $options['body'];
        }

        $response = $this->httpClient->request($method, $url, $requestOptions);

        return json_decode($response->getBody()->getContents(), true);
    }

    protected function getToken(string $refreshToken = '')
    {
        if (! empty($refreshToken)) {
            $url = '/v1.0/token/'.$refreshToken;
        } else {
            $url = '/v1.0/token?grant_type=1';
        }

        $response = $this->httpClient->get($url, [
            'headers' => $this->getHeaders('GET', $url),
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }

    public function getHeaders(string $method, string $url, string $body = '', array $appendHeaders = [])
    {
        if (substr($url, 0, 11) == '/v1.0/token') {
            $accessToken = '';
        } else {
            $accessToken = Cache::get(self::KEY_TU_YA_ACCESS_TOKEN);
        }

        $nonce = Str::random();
        $t = intval(microtime(true) * 1000);

        $headersStr = '';
        if (! empty($appendHeaders)) {
            foreach ($appendHeaders as $k => $v) {
                $headersStr .= $k.':'.$v."\n";
            }
            $headersStr = rtrim($headersStr, "\n");
        }

        $headers = [
            'client_id' => $this->accessId,
            'sign_method' => 'HMAC-SHA256',
            't' => $t,
            'nonce' => $nonce,
        ];
        if (! empty($accessToken)) {
            $headers['access_token'] = $accessToken;
        }

        $stringToSing = $method."\n".hash('sha256', $body)."\n".$headersStr."\n".$url;

        $sign = strtoupper(hash_hmac('sha256', $this->accessId.$accessToken.$t.$nonce.$stringToSing, $this->accessSecret));
        $headers['sign'] = $sign;

        return $headers;
    }
}