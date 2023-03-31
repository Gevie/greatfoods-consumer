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

class TokenResolver implements TokenResolverInterface
{
    public function __construct(
        protected AdapterInterface $cache,
        protected ClientInterface $client,
        protected array $authDetails
    ) {
        $this->validateAuthDetails();
    }

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