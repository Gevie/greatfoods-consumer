<?php

namespace GreatFoods\APIHandler\Contracts\Services;

/**
 * ApiService Interface
 *
 * @package GreatFoods\APIHandler\Contracts\Services
 * @author Stephen Speakman <hellospeakman@gmail.com>
 */
interface ApiService
{
    /**
     * Send a request and return the response body
     *
     * @param string $method The request method to use (i.e. GET, PUT)
     * @param string $endpoint The endpoint to call
     * @param array $data The data to send
     *
     * @return array The response
     */
    public function call(string $method, string $endpoint, array $data = []): array;
}