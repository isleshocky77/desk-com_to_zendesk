<?php

namespace isleshocky77\Zendesk\Api;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\HandlerStack;
use Psr\Http\Message\RequestInterface;

class ZendeskClient
{
    private static $_instance;

    public function __construct()
    {
        $stack = HandlerStack::create();
        $stack->setHandler(new CurlHandler());
        $authorization = base64_encode(sprintf('%s/token:%s', getenv('ZENDESK_EMAIL_ADDRESS'), getenv('ZENDESK_TOKEN')));
        $stack->push(self::addHeader('Authorization', 'Basic '.$authorization));

        self::$_instance = new Client([
            'base_uri' => getenv('ZENDESK_BASE_URI'),
            'handler' => $stack,
        ]);
    }

    private static function addHeader($header, $value)
    {
        return function (callable $handler) use ($header, $value) {
            return function (
                RequestInterface $request,
                array $options
            ) use ($handler, $header, $value) {
                $request = $request->withHeader($header, $value);

                return $handler($request, $options);
            };
        };
    }

    public static function getInstance()
    {
        if (is_null(self::$_instance)) {
            new self();
        }

        return self::$_instance;
    }
}
