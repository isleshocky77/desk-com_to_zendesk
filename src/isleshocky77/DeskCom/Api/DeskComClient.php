<?php

namespace isleshocky77\DeskCom\Api;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Subscriber\Oauth\Oauth1;

class DeskComClient
{
    private static $_instance;

    private $client;

    private static $articles;

    private static $topics;

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

        $this->client =  new Client([
            'base_uri' => getenv('DESK_COM_BASE_URI'),
            'handler' => $stack,
        ]);
    }

    public static function getInstance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    public function findAllArticles($forceRefresh = false)
    {
        if (!is_null(self::$articles) && !$forceRefresh) {
            return self::$articles;
        }

        self::$articles = [];

        $uri = 'articles';
        do {
            $response = $this->client->get($uri, ['auth' => 'oauth']);
            $payload = json_decode((string) $response->getBody());

            $articles = $payload->_embedded->entries;

            foreach ($articles as $article) {
                self::$articles[] = $article;
            }

        } while (null !== ($uri = $payload->_links->next->href));

        return self::$articles;
    }

    public function findAllTopics($forceRefresh = false)
    {
        if (!is_null(self::$topics) && !$forceRefresh) {
            return self::$topics;
        }

        self::$topics = [];

        $uri = 'topics';
        do {
            $response = $this->client->get($uri, ['auth' => 'oauth']);
            $payload = json_decode((string) $response->getBody());

            $topics = $payload->_embedded->entries;

            foreach ($topics as $topic) {
                self::$topics[] = $topic;
            }

        } while (null !== ($uri = $payload->_links->next->href));

        return self::$topics;
    }
}
