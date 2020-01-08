<?php

namespace isleshocky77\DeskCom\Api;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Subscriber\Oauth\Oauth1;

class DeskComClient
{
    private static $_instance;

    public function __construct()
    {
        $stack = HandlerStack::create();
        $middleware = new Oauth1([
            'consumer_key' => getenv('DESK_COM_CONSUMER_KEY'),
            'consumer_secret' => getenv('DESK_COM_CONSUMER_SECRET'),
            'token' => getenv('DESK_COM_ACCESS_TOKEN'),
            'token_secret' => getenv('DESK_COM_ACCESS_TOKEN_SECRET'),
        ]);

        $stack->push($middleware);

        self::$_instance = new Client([
            'base_uri' => getenv('DESK_COM_BASE_URI'),
            'handler' => $stack,
        ]);
    }

    public static function getInstance()
    {
        if (is_null(self::$_instance)) {
            new self();
        }

        return self::$_instance;
    }
}
