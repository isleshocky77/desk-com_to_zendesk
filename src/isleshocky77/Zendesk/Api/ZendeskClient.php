<?php

namespace isleshocky77\Zendesk\Api;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\HandlerStack;
use Psr\Http\Message\RequestInterface;

class ZendeskClient
{
    private static $_instance;

    private $client;

    private static $categories;

    private static $sections;

    public function __construct()
    {
        $stack = HandlerStack::create();
        $stack->setHandler(new CurlHandler());
        $authorization = base64_encode(sprintf('%s/token:%s', getenv('ZENDESK_EMAIL_ADDRESS'), getenv('ZENDESK_TOKEN')));
        $stack->push(self::addHeader('Authorization', 'Basic '.$authorization));

        $this->client = new Client([
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

    public static function getInstance():ZendeskClient
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    public function getAllCategories($locale = 'en-us', $forceRefresh = false)
    {
        if (!is_null(self::$categories) && !$forceRefresh) {
            return self::$categories;
        }

        self::$categories = [];

        $uri = sprintf('/api/v2/help_center/%s/categories.json', $locale);

        do {
            $response = $this->client->get($uri);
            $payload = json_decode((string) $response->getBody());

            $categories = $payload->categories;

            foreach ($categories as $category) {
                self::$categories[] = $category;
            }
        } while (null !== ($uri = $payload->next_page));

        return self::$categories;
    }

    public function getAllSections($locale = 'en-us', $forceRefresh = false)
    {
        if (!is_null(self::$sections) && !$forceRefresh) {
            return self::$sections;
        }

        self::$sections = [];

        $uri = sprintf('/api/v2/help_center/%s/sections.json', $locale);

        do {
            $response = $this->client->get($uri);
            $payload = json_decode((string) $response->getBody());

            $sections = $payload->sections;

            foreach ($sections as $section) {
                self::$sections[] = $section;
            }
        } while (null !== ($uri = $payload->next_page));

        return self::$sections;
    }

    public function createSection(string $categoryId, string $name, string $description, int $position, $locale = 'en-us')
    {
        $uri = sprintf('/api/v2/help_center/%s/categories/%s/sections.json', $locale, $categoryId);
        $response = $this->client->post($uri, [
            'json' => [
                'section' => [
                    'name' => $name,
                    'description' => $description,
                    'position' => $position,
                ]
            ]
        ]);

        $payload = json_decode((string) $response->getBody());
        $sections = $payload->section;

        return $sections;
    }

    public function findSectionForCategoryByName(int $categoryId, string $name): ?\stdClass
    {
        $sections = $this->getAllSections();
        $categorySections = array_filter($sections, function ($section) use ($categoryId) {
            return $section->category_id === (int) $categoryId;
        });

        $matchingSections = array_filter($categorySections, function ($section) use ($name) {
            return $name === $section->name;
        });

        if (count($matchingSections) > 1) {
            throw new \RuntimeException(sprintf('WARNING: Topic "%s" exists %d times', $name, count($matchingSections)));
        } elseif (count($matchingSections) == 1) {
            $matchingSection = array_pop($matchingSections);
        } else {
            $matchingSection = null;
        }

        return $matchingSection;
    }

    public function getAllArticles($locale = 'en-us', $forceRefresh = false)
    {
        if (!is_null(self::$articles) && !$forceRefresh) {
            return self::$articles;
        }

        self::$articles = [];

        $uri = sprintf('/api/v2/help_center/%s/articles.json', $locale);

        do {
            $response = $this->client->get($uri);
            $payload = json_decode((string) $response->getBody());

            $articles = $payload->sections;

            foreach ($articles as $article) {
                self::$articles[] = $article;
            }
        } while (null !== ($uri = $payload->next_page));

        return self::$articles;
    }
}
