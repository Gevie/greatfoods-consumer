<?php

declare(strict_types=1);

namespace GreatFoods\APIHandler\Services\Resolvers;

use GreatFoods\APIHandler\Contracts\Services\Resolvers\TokenResolver as TokenResolverInterface;
use GreatFoods\APIHandler\Exceptions\RequestException;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use InvalidArgumentException;
use JsonException;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Contracts\Cache\ItemInterface;

/**
 * TokenResolver Class
 *
 * @package GreatFoods\APIHandler\Services\Resolvers
 * @author Stephen Speakman <hellospeakman@gmail.com>
 */
class TokenResolver implements TokenResolverInterface
{
    /**
     * Constructor.
     *
     * @param AdapterInterface $cache The cache adapter
     * @param ClientInterface $client The HTTP client
     * @param array $authDetails The authentication details
     */
    public function __construct(
        protected AdapterInterface $cache,
        protected ClientInterface $client,
        protected array $authDetails
    ) {
        $this->validateAuthDetails();
    }

    /**
     * Validates that the authDetails array has all required keys.
     *
     * @return void
     * 
     * @throws InvalidArgumentException If any of the required keys are missing
     */
    protected function validateAuthDetails(): void
    {
        $missingAuthDetails = array_diff(self::REQUIRED_AUTH_DETAILS, array_keys($this->authDetails));

        if ($missingAuthDetails) {
            throw new InvalidArgumentException(sprintf(
                'The following required keys are missing from the $authDetails array: "%s"',
                implode(', ', $missingAuthDetails)
            ));
        }
    }

    /**
     * Gets the access token for the API.
     *  Loads from cache otherwise make a new request
     *
     * @return array An array of token details
     * 
     * @throws RequestException If the request fails
     * @throws RequestException If the response could not be decoded
     */
    public function getToken(): array
    {
        return $this->cache->get('bearer_token', static function(ItemInterface $item): array {
            $url = sprintf('%s/%s', $this->authDetails['base_url'], $this->authDetails['auth_endpoint']);

            try {
                $response = $this->client->request('POST', $url, [
                    'json' => [
                        'client_secret' => $this->authDetails['client_secret'],
                        'client_id' => $this->authDetails['client_id'],
                        'grant_type' => $this->authDetails['grant_type']
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

            $item->expiresAfter((int) $token['expires_in']);

            return $token;
        });
    }
}