<?php

namespace GreatFoods\APIHandler\Contracts\Models;

interface Menu
{
    public function getId(): string;

    public function getName(): string;
}