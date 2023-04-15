<?php

declare(strict_types=1);

namespace App\Post\Application\Shared\DTO;

use Symfony\Component\Uid\Uuid;

class Post
{
    public function __construct(
        public readonly ?Uuid $id,
        public readonly string $title,
        public readonly string $summary,
    )
    {
    }
}