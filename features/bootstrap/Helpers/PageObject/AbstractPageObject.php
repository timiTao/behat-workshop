<?php
namespace Helpers\PageObject;

use Helpers\Client;
use Psr\Http\Message\ResponseInterface;

abstract class AbstractPageObject implements PageObjectInterface
{
    /** @var  ResponseInterface */
    protected $response;
    /** @var  Client */
    private $client;

    public function printRequest(): string
    {
        return $this->client->printResponse();
    }

    protected function getClient(): Client
    {
        return $this->client;
    }

    public function setClient(Client $client)
    {
        $this->client = $client;
    }
}