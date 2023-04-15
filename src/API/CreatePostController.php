<?php

declare(strict_types=1);

namespace App\API;

use App\Post\Application\Command\CreatePostCommand;
use App\Shared\Domain\Bus\CommandBus;
use Exception;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;

#[Route(path: '/posts', methods: ['POST'], format: 'json')]
#[OA\Tag("Post Data")]
#[OA\Post(
    path: '/posts',
    description: "Add data to database",
    requestBody: new OA\RequestBody(
        description: "Add title, summary and description to post data to DB",
        required: true,
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: "title", description: "Enter Title", type: "string"),
                new OA\Property(property: "summary", description: "Enter Summary", type: "string"),
                new OA\Property(property: "description", description: "Enter Description", type: "string")
            ],
            type: "object",
            example: [
                "title" => "Title",
                "summary" => "Test Summary",
                "description" => "Test Description"
            ]
        )
    ),
    responses: [
        new OA\Response(
            response: 200,
            description: 'Returns the post ID as UUID',
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'post_id', description: "Newly generated UUID", type: "string")
                ],
                type: 'object'
            )
        ),
        new OA\Response(
            response: 400,
            description: "Title should not start with Qwerty OR Request should contain a body"
        )
    ]
)]
class CreatePostController
{
    public function __construct(
        private readonly CommandBus $commandBus
    ) {
    }

    public function __invoke(Request $request): JsonResponse
    {
        $payload = $request->toArray();

        if (empty($payload)) {
            return new JsonResponse(
                [
                    'error' => 'Request should contain body'
                ],
                Response::HTTP_BAD_REQUEST
            );
        }

        // Feature 2:
        // Should be confirmed with PM, what if title starts with lowercase qwerty or QWERTY or QwERtY,
        // but now assuming that the word should exactly match 'Qwerty'
        if (str_starts_with($payload['title'], 'Qwerty'))
        {
            return new JsonResponse(
                [
                    'error' => 'Title cannot start with Qwerty'
                ],
                Response::HTTP_BAD_REQUEST
            );
        }

        $command = new CreatePostCommand(
            id: $payload['id'] ?? (string)Uuid::v4(),
            title: $payload['title'],
            summary: $payload['summary'],
            description: $payload['description'],
        );

        try {
            $this->commandBus->dispatch(
                command: $command,
            );
        } catch (Exception $exception) {
            return new JsonResponse(
                [
                    'error' => $exception->getMessage(),
                ],
                Response::HTTP_BAD_REQUEST,
            );
        }

        return new JsonResponse(
            [
                'post_id' => $command->id,
            ],
            Response::HTTP_OK,
        );
    }
}
