<?php

declare(strict_types=1);

namespace AstrobinWs\Response;

use AstrobinWs\Exceptions\WsResponseException;

/**
 * Class AbstractResponse
 * @package Astrobin\Response
 */
abstract class AbstractResponse
{

    /**
     * @throws WsResponseException
     */
    public function fromObj(\stdClass $obj): void
    {
        $this->fromArray((array)$obj);
    }


    /**
     * Build properties of class based on WS response
     * @throws WsResponseException
     */
    private function fromArray(array $objArr): void
    {
        $reflector = new \ReflectionClass($this);
        foreach ($reflector->getProperties() as $property) {
            if (!array_key_exists($property->getName(), $objArr)) {
                throw new WsResponseException(
                    sprintf("Property \"%s\" doesn't exist in class %s", $property->getName(), static::class),
                    500,
                    null
                );
            }

            $property->setValue($this, $objArr[$property->getName()]);
        }
    }
}
