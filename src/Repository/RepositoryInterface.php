<?php

namespace App\Repository;

interface RepositoryInterface
{
    public function exist(string $uuid): bool;

    public function find(string $uuid): string;

    public function save(string $uuid, int $ttl, string $data): void;
}
