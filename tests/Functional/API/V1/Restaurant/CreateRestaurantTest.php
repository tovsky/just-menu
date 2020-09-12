<?php

namespace App\Tests\Functional\API\V1\Restaurant;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CreateRestaurantTest extends WebTestCase
{
    private const URL_METHOD = 'http://nginx/api/v1/restaurant';

    /**
     * @dataProvider additionProviderSuccessCreate
     * @group regression
     * @group create-restaurant
     */
    public function testSuccessCreateRestaurant($content)
    {
        $client = static::createClient();

        $client->request(
            Request::METHOD_POST,
            self::URL_METHOD,
            [],
            [],
            [],
            $content
        );
        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
    }

    /**
     * @dataProvider additionProviderErrorCreate
     * @group regresssion
     * @group create-restaurant
     */
    public function testErrorsCreateRestaurant($content)
    {
        $client = static::createClient();

        $client->request(
            Request::METHOD_POST,
            self::URL_METHOD,
            [],
            [],
            [],
            $content
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }

    public function additionProviderSuccessCreate()
    {
        return [
            ['{
                "name":"testRest",
                "address": "Улица Пушкина",
                "email": "test@yandex.ru"
            }'],
        ];
    }

    public function additionProviderErrorCreate()
    {
        return [
            ['{
                "name":"",
                "address": "Улица Пушкина",
                "email": "test@yandex.ru"
            }'],
            ['{
                "name":"testRest",
                "address": "",
                "email": "test@yandex.ru"
            }'],
            ['{
                "name":"testRest",
                "address": "Улица Пушкина",
                "email": ""
            }'],
            ['{
                "name":"testRest",
                "address": "Улица Пушкина",
                "email": "11111"
            }'],
        ];
    }
}