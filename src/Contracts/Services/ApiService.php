<?php

namespace GreatFoods\APIHandler\Contracts\Services;

interface ApiService
{
    public function call(string $method, string $endpoint, array $data = []): array;
}