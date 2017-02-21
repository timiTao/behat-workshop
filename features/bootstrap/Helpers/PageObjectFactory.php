<?php
namespace Helpers;


use Helpers\PageObject\PageObjectInterface;

class PageObjectFactory
{
    /** @var  Client */
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function factory(string $name): PageObjectInterface
    {
        $page = new $name();
        if (!$page instanceof PageObjectInterface) {
            throw new \Exception('Given class is not instance of PageObjectInterface: ' . $name);
        }
        $page->setClient($this->client);
        return $page;
    }
}
