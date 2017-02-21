<?php
namespace Helpers;

use Interop\Container\ContainerInterface;

class Container extends \ArrayObject implements ContainerInterface
{
    public function get($id)
    {
        return $this->offsetGet($id);
    }

    public function has($id)
    {
        return $this->offsetExists($id);
    }

    public function set(string $id, $service)
    {
        $this->offsetSet($id, $service);
    }
}