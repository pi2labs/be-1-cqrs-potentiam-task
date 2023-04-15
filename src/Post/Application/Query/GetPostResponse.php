<?php

namespace App\Post\Application\Query;


use App\Post\Application\Shared\DTO\Post;
use App\Shared\Domain\Bus\Response;

class GetPostResponse implements Response
{
    public function __construct(public readonly Post $post)
    {
    }
}