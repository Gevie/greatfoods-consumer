<?php

declare(strict_types=1);

namespace GreatFoods\APIHandler\Tests\Unit\Services\Resolvers;

use GreatFoods\APIHandler\Contracts\Services\Resolvers\TokenResolver as TokenResolverInterface;
use GreatFoods\APIHandler\Exceptions\RequestException;
use GreatFoods\APIHandler\Services\Resolvers\TokenResolver;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ServerException;
use InvalidArgumentException;
use Mockery;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Cache\Adapter\ArrayAdapter;

class TokenResolverTest extends TestCase
{
    protected TokenResolverInterface $tokenResolver;

    protected ClientInterface $client;

    protected array $authDetails;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = Mockery::mock(ClientInterface::class);
        $this->authDetails = [
            'base_url' => 'https://test.com',
            'auth_endpoint' => 'auth',
            'client_secret' => 'test_secret',
            'client_id' => 'test_id',
            'grant_type' => 'test_grant_type'
        ];

        $this->tokenResolver = new TokenResolver(
            new ArrayAdapter(),
            $this->client,
            $this->authDetails
        );
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        Mockery::close();
    }

    public function testGetToken(): void
    {
        // Arrange
        $responseBody = json_encode([
            'access_token' => 'test_token',
            'expires_in' => 3600,
            'token_type' => 'bearer'
        ]);

        $response = Mockery::mock(ResponseInterface::class);
        $response->shouldReceive('getBody')
            ->andReturn($responseBody);

        $this->client->shouldReceive('request')
            ->with('POST', 'https://test.com/auth', [
                'json' => [
                    'client_secret' => 'test_secret',
                    'client_id' => 'test_id',
                    'grant_type' => 'test_grant_type'
                ],
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded'
                ]
            ])
            ->andReturn($response);

        // Act
        $result = $this->tokenResolver->getToken();

        // Assert
        $this->assertIsArray($result);
        $this->assertArrayHasKey('access_token', $result);
        $this->assertEquals('test_token', $result['access_token']);
    }

    public function testGetTokenThrowsExceptionWhenMissingAuthDetails(): void
    {
        // Assert
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The following required keys are missing from the $authDetails array: "client_secret"');
        
        // Arrange
        $authDetails = [
            'base_url' => 'https://test.com',
            'auth_endpoint' => 'auth',
            'client_id' => 'test_id',
            'grant_type' => 'test_grant_type'
        ];

        // Act
        new TokenResolver(new ArrayAdapter(), $this->client, $authDetails);
    }

    public function testGetTokenThrowsExceptionWhenRequestFails(): void
    {
        // Assert
        $this->expectException(RequestException::class);
        $this->expectExceptionMessage('Could not request token, message: "test_error"');

        // Arrange
        $request = Mockery::mock(RequestInterface::class);
        $response = Mockery::mock(ResponseInterface::class);

        $response->shouldReceive('getStatusCode')
            ->andReturn(500);

        $this->client->shouldReceive('request')
            ->andThrow(new ServerException('test_error', $request, $response));

        // Act
        $this->tokenResolver->getToken();
    }

    public function testGetTokenThrowsExceptionWhenJsonDecodeFails(): void
    {
        // Assert
        $this->expectException(RequestException::class);
        $this->expectExceptionMessage('Could not decode json response body.');

        // Arrange
        $response = Mockery::mock(ResponseInterface::class);
        $response->shouldReceive('getBody')
            ->andReturn('invalid_json');

        $this->client->shouldReceive('request')
            ->once()
            ->andReturn($response);

        // Act
        $this->tokenResolver->getToken();
    }

    public function testGetTokenReturnsCachedToken(): void
    {
        // Arrange
        $responseBody = json_encode([
            'access_token' => 'test_token',
            'expires_in' => 3600,
            'token_type' => 'bearer'
        ]);

        $response = Mockery::mock(ResponseInterface::class);
        $response->shouldReceive('getBody')
            ->andReturn($responseBody);

        $this->client->shouldReceive('request')
            ->once()
            ->with('POST', 'https://test.com/auth', [
                'json' => [
                    'client_secret' => 'test_secret',
                    'client_id' => 'test_id',
                    'grant_type' => 'test_grant_type'
                ],
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded'
                ]
            ])
            ->andReturn($response);

        // Act
        $result = $this->tokenResolver->getToken();

        // Assert
        $this->assertIsArray($result);
        $this->assertArrayHasKey('access_token', $result);
        $this->assertEquals('test_token', $result['access_token']);

        // Arrange
        $this->client = Mockery::mock(ClientInterface::class);

        $this->client->shouldReceive('request')
            ->andThrow(new \Exception('Request should not be made when cached token is available'));

        // Act
        $result2 = $this->tokenResolver->getToken();

        // Assert
        $this->assertIsArray($result2);
        $this->assertArrayHasKey('access_token', $result2);
        $this->assertEquals('test_token', $result2['access_token']);
    }
}