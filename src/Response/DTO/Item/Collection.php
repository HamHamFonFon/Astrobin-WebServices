<?php

declare(strict_types=1);

namespace AstrobinWs\Response\DTO\Item;

use AstrobinWs\Filters\QueryFilters;
use AstrobinWs\Response\AbstractResponse;
use AstrobinWs\Response\DTO\AstrobinResponse;
use AstrobinWs\Response\DTO\Collection\ListImages;

/**
 * Class Collection
 * @package Astrobin\Response
 */
final class Collection extends AbstractResponse implements AstrobinResponse
{
    public int $id;

    public string $name;

    public ?string $description = null;


    public string $user;

    public string|null|\DateTime $date_created = null;

    public string|null|\DateTime $date_updated = null;

    public ListImages|array|null $images = null;

    public function setDateCreated(?string $dateCreated): self
    {
        $this->date_created = ((is_null($dateCreated)) ? null : \DateTime::createFromFormat(QueryFilters::DATE_FORMAT->value, $dateCreated)) ?: null;
        return $this;
    }

    public function setDateUpdated(?string $dateUpdated): self
    {
        $this->date_updated = ((is_null($dateUpdated)) ? null : \DateTime::createFromFormat(QueryFilters::DATE_FORMAT->value, $dateUpdated)) ?: null;
        return $this;
    }
}
