<?php

namespace GreatFoods\APIHandler\Contracts\Services\Resolvers;

interface TokenResolver
{
    public const REQUIRED_AUTH_DETAILS = [
        'base_url',
        'auth_endpoint',
        'client_secret',
        'client_id',
        'grant_type'
    ];

    public function getToken(): array;
}