<?php

declare(strict_types=1);

namespace App\Post\Application\Shared\Transformer;

use App\Post\Application\Shared\DTO\Post as PostDto;
use App\Post\Domain\Post;

class PostTransformer
{
    // Transform entity to Application DTO
    public function transformToDto(Post $post): PostDto
    {
        return new PostDto(
            $post->getId(),
            $post->getTitle(),
            $post->getSummary(),
        );
    }
}