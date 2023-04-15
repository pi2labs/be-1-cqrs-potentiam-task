<?php

declare(strict_types=1);

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use Symfony\Component\HttpFoundation\Response;

class GetPostControllerTest extends ApiTestCase
{
    public function testGetDataWithParameter(): void
    {
        $response = static::createClient()->request('POST', '/posts', ['json' => [
            'title' => 'My first post',
            'summary' => 'Nam non est risus. Donec at orci at lectus consequat scelerisque vel ac justo.',
            'description' => 'Aliquam erat volutpat. Fusce ut porta quam, eget pulvinar lectus. Vivamus et sapien libero. Morbi ullamcorper congue diam, ac dapibus tellus dignissim eu.',
        ]]);

        $postDataResponse = $response->toArray();

        $response = static::createClient()->request('GET', '/posts/' . $postDataResponse['post_id']);

        $getDataResponse = $response->toArray();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals($postDataResponse['post_id'], $getDataResponse['post_id']);
        $this->assertEquals("My first post", $getDataResponse['title']);
        $this->assertEquals('Nam non est risus. Donec at orci at lectus consequat scelerisque vel ac justo.',
            $getDataResponse['summary']);
    }

    public function testGetDataWhenPostIdIsInvalidUUID(): void
    {
        $response = static::createClient()->request('GET', '/posts/12345');

        $this->assertEquals("Invalid UUID", $response->toArray(false)['error']);
        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }

    public function testGetDataWhenUUIDIsValidAndIsNotPresent(): void
    {
        $response = static::createClient()->request('GET', '/posts/dc6a86fe-2664-440f-b4d9-e1241ee0f798');
        $this->assertEmpty($response->toArray(false));
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }
}
