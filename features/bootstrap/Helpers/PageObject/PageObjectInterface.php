<?php

namespace Helpers\PageObject;

use Helpers\Client;

interface PageObjectInterface
{
    public function setClient(Client $client);

    public function isValid(): bool;
}