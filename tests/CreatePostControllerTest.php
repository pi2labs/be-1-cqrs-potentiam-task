<?php

declare(strict_types=1);

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\Uuid;

class CreatePostControllerTest extends ApiTestCase
{
    public function testPostData(): void
    {
        $response = static::createClient()->request('POST', '/posts', ['json' => [
            'title' => 'My first post',
            'summary' => 'Nam non est risus. Donec at orci at lectus consequat scelerisque vel ac justo.',
            'description' => 'Aliquam erat volutpat. Fusce ut porta quam, eget pulvinar lectus. Vivamus et sapien libero. Morbi ullamcorper congue diam, ac dapibus tellus dignissim eu.',
        ]]);

        $data = $response->toArray();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertTrue(UUID::isValid($data['post_id']));
    }


    public function testWhenTitleStartsWithQwerty(): void
    {
        $response = static::createClient()->request('POST', '/posts', ['json' => [
            'title' => 'Qwerty is at the beginning',
            'summary' => 'Nam non est risus. Donec at orci at lectus consequat scelerisque vel ac justo.',
            'description' => 'Aliquam erat volutpat. Fusce ut porta quam, eget pulvinar lectus. Vivamus et sapien libero. Morbi ullamcorper congue diam, ac dapibus tellus dignissim eu.',
        ]]);

        $this->assertEquals("Title cannot start with Qwerty", $response->toArray(false)['error']);
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }
}
