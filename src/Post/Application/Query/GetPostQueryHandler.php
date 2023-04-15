<?php

declare(strict_types=1);

namespace App\Post\Application\Query;


use App\Post\Application\Shared\DTO\Post;
use App\Post\Application\Shared\Transformer\PostTransformer;
use App\Post\Domain\PostRepository;
use App\Shared\Domain\Bus\QueryHandler;
use Symfony\Component\Uid\Uuid;

class GetPostQueryHandler implements QueryHandler
{
    public function __construct(
        private readonly PostRepository $repository,
        private readonly PostTransformer $postTransformer,
    ) {
    }

    public function __invoke(GetPostQuery $query): ?GetPostResponse
    {
        $result = $this->repository->find(Uuid::fromString($query->id));
        if (!$result) {
            return null;
        }

        $post = $this->postTransformer->transformToDto($result);

        return new GetPostResponse($post);
    }
}