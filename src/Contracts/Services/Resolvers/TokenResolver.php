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
    /**
     * Gets the access token for the API
     *
     * @return array An array of token details
     */
    public function getToken(): array;
}