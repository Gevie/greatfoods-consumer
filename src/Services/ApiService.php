<?php

declare(strict_types=1);

namespace GreatFoods\APIHandler\Services;

use GreatFoods\APIHandler\Contracts\Services\ApiService as ApiServiceInterface;
use GreatFoods\APIHandler\Contracts\Services\Resolvers\TokenResolver as TokenResolverInterface;
use GreatFoods\APIHandler\Exceptions\RequestException;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use JsonException;

abstract class ApiService implements ApiServiceInterface
{
    public function __construct(
        protected ClientInterface $client,
        protected TokenResolverInterface $tokenResolver,
        protected string $url
    ) {
        // ...
    }

    public function call(string $method, string $endpoint, array $data = []): array
    {
        $url = sprintf('%s/%s', $this->url, $endpoint);
        $token = $this->tokenResolver->getToken();

        try {
            $response = $this->client->request($method, $url, [
                'json' => $data,
                'headers' => [
                    'Authorization' => sprintf('%s %s', $token['token_type'], $token['access_token'])
                ]
            ]);
        } catch (GuzzleException $exception) {
            throw new RequestException(sprintf('Request issue, message: "%s"', $exception->getMessage()), $exception->getCode());
        }

        try {
            $responseData = json_decode((string)$response->getBody(), true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $exception) {
            throw new RequestException('Could not decode json response body.', $exception->getCode());
        }

        return $responseData;
    }
}