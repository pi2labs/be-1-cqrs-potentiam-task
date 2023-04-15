<?php

namespace App\API;

use App\Post\Application\Query\GetPostQuery;
use App\Post\Application\Query\GetPostResponse;
use App\Shared\Domain\Bus\QueryBus;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use OpenApi\Attributes as OA;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;

#[Route(path: '/posts/{postId}', methods: ['GET'], format: 'json')]
#[OA\Tag("Get Data")]
#[OA\Response(
    response: 200,
    description: 'Successfully fetched the data',
    content:new OA\JsonContent(
        properties: [
            new OA\Property(property: "post_id", description: "UUID", type: "string"),
            new OA\Property(property: "title", description: "Title of the data", type: "string"),
            new OA\Property(property: "summary", description: "Summary of the data", type: "string"),
        ],
        type: 'object'
    )
)]
#[OA\Response(
    response: 404,
    description: "ID is not present in the database",
    content: []
)]
#[OA\Response(
    response: 400,
    description: "Invalid UUID provided",
)]

#[AsController]
class GetPostController
{
    public function __construct(private readonly QueryBus $queryBus)
    {
    }

    public function __invoke(Request $request, string $postId): JsonResponse
    {

        if (!Uuid::isValid($postId)){
            return new JsonResponse(
                [
                    'error' => 'Invalid UUID',
                ],
                Response::HTTP_BAD_REQUEST,
            );
        }

        $query = new GetPostQuery($postId);

        /** @var GetPostResponse $getQueryResponse */
        $getQueryResponse = $this->queryBus->ask($query);

        if (!$getQueryResponse) {
            return new JsonResponse(
                status: Response::HTTP_NOT_FOUND,
            );
        }

        return new JsonResponse(
            [
                'post_id' => $getQueryResponse->post->id,
                'title' => $getQueryResponse->post->title,
                'summary' => $getQueryResponse->post->summary,
            ],
            Response::HTTP_OK,
        );
    }
}