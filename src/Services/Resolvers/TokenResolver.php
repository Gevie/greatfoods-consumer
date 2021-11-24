<?php

declare(strict_types=1);

namespace GreatFoods\APIHandler\Services\Resolvers;

use GreatFoods\APIHandler\Contracts\Services\Resolvers\TokenResolver as TokenResolverInterface;
use GreatFoods\APIHandler\Exceptions\RequestException;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use JsonException;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Contracts\Cache\ItemInterface;

/**
 * TokenResolver Class
 *
 * @package GreatFoods\APIHandler\Services\Resolvers;
 * @author Stephen Speakman <hellospeakman@gmail.com>
 */
class TokenResolver implements TokenResolverInterface
{
    /**
     * The cache adapter
     *
     * @var AdapterInterface
     */
    protected AdapterInterface $cache;

    /**
     * The Guzzle client
     *
     * @var ClientInterface
     */
    protected ClientInterface $client;

    /**
     * An array of auth details
     *
     * @var array
     */
    protected array $authDetails;

    /**
     * Constructor
     *
     * @param AdapterInterface $cache The cache adapter
     * @param ClientInterface $client The Guzzle client
     * @param string $baseUrl The API base url
     * @param string $authEndpoint The API auth endpoint
     * @param string $clientSecret The client secret
     * @param string $clientId The client id
     * @param string $grantType The grant type
     */
    public function __construct(
        AdapterInterface $cache,
        ClientInterface $client,
        string $baseUrl,
        string $authEndpoint,
        string $clientSecret,
        string $clientId,
        string $grantType
    ) {
        $this->cache = $cache;
        $this->client = $client;
        $this->authDetails = [
            'base_url' => $baseUrl,
            'auth_endpoint' => $authEndpoint,
            'client_secret' => $clientSecret,
            'client_id' => $clientId,
            'grant_type' => $grantType
        ];
    }

    /**
     * Gets the access token for the API
     *  Loads from cache otherwise make a new request
     *
     * @return array An array of token details
     */
    public function getToken(): array
    {
        $self =& $this;

        return $this->cache->get('bearer_token', static function(ItemInterface $item) use (&$self) {
            $url = sprintf('%s/%s', $self->authDetails['base_url'], $self->authDetails['auth_endpoint']);

            try {
                $response = $self->client->request('POST', $url, [
                    'json' => [
                        'client_secret' => $self->authDetails['client_secret'],
                        'client_id' => $self->authDetails['client_id'],
                        'grant_type' => $self->authDetails['grant_type']
                    ],
                    'headers' => [
                        'Content-Type' => 'application/x-www-form-urlencoded'
                    ]
                ]);
            } catch (GuzzleException $exception) {
                throw new RequestException(sprintf('Could not request token, message: "%s"', $exception->getMessage()));
            }

            try {
                $token = json_decode((string)$response->getBody(), true, 512, JSON_THROW_ON_ERROR);
            } catch (JsonException $exception) {
                throw new RequestException('Could not decode json response body.');
            }

            // IDE will complain about this since it isn't a void return type
            $item->expiresAfter((int) $token['expires_in']);

            return $token;
        });
    }
}