<?php

namespace App\Model\Traits;

use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV6;

trait UuidTrait
{
    private UuidV6 $uuid;

    public function __construct()
    {
        $this->uuid = Uuid::v6();
    }

    public function getUuid(): UuidV6
    {
        return $this->uuid;
    }
}
