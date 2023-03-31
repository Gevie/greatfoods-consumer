<?php

namespace GreatFoods\APIHandler\Contracts\Models;

interface Product
{
    public function getId(): string;

    public function getName(): string;
}