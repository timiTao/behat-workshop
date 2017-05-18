<?php
namespace Helpers;

use GuzzleHttp\Client;
use Helpers\PageObject\HelloWord;
use Interop\Container\ContainerInterface;

class ContainerFactory
{
    static public function factory(): ContainerInterface
    {
        $container = new Container();
        $container->set(\Helpers\Client::class,
            new \Helpers\Client(
                new Client(
                    ['base_uri' => 'http://172.17.0.1/']
                )
            )
        );

        $container->set(
            PageObjectFactory::class,
            new PageObjectFactory(
                $container->get(\Helpers\Client::class)
            )
        );

        self::setPageObject($container, HelloWord::class);

        return $container;
    }

    static private function setPageObject(Container $container, string $pageObjectName)
    {
        $container->set($pageObjectName, $container->get(PageObjectFactory::class)->factory($pageObjectName));
    }

}