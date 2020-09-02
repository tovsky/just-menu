<?php

namespace App\Tests\Functional\API\V1\User;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CreateUserTest extends WebTestCase
{
    public function testSuccessCreateUser()
    {
        $client = static::createClient();

        $client->request(
            Request::METHOD_POST,
            '/api/v1/users/',
            [],
            [],
            [],
            '{
                "name": "test79 user",
                "email" : "test779@test.ru",
                "phone" : "99977392292929",
                "position" : "administrator",
                "password" : "1111"
            }'
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
    }
}
