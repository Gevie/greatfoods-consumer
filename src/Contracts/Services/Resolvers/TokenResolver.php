<?php

namespace GreatFoods\APIHandler\Contracts\Services\Resolvers;

/**
 * TokenResolver Interface
 *
 * @package GreatFoods\APIHandler\Contracts\Services\Resolvers;
 * @author Stephen Speakman <hellospeakman@gmail.com>
 */
interface TokenResolver
{
    public const REQUIRED_AUTH_DETAILS = [
        'base_url',
        'auth_endpoint',
        'client_secret',
        'client_id',
        'grant_type'
    ];

    /**
     * Gets the access token for the API
     *
     * @return array An array of token details
     */
    public function getToken(): array;
}