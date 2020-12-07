<?php
declare(strict_types=1);

namespace AstrobinWs\Services;

use AstrobinWs\Response\AstrobinResponse;

/**
 * Interface WsInterface
 *
 * @package Astrobin
 */
interface WsInterface
{
    public function getById(int $id): ?AstrobinResponse;
    public function buildResponse(string $object): ?AstrobinResponse;
}
