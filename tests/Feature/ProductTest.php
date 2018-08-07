<?php

namespace Tests\Feature;

use Tests\TestCase;

class ProductTest extends TestCase
{
    /**
     * @dataProvider dataStoreProvider
     *
     * @throws \Exception
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public function testStoreTest($input, $output)
    {
        $response = $this->json('POST', '/api/product', $input);

        $response->assertStatus(400)
                 ->assertExactJson($output);
    }

    public function dataStoreProvider()
    {
        return [
            // test #1
            [
                'input'  => [
                    'description' => 'description',
                    'price'       => 3
                ],
                'output' => [
                    'message' => ['The name field is required.']
                ]
            ],
            // test #2
            [
                'input'  => [
                    'name'  => 'name',
                    'price' => 3.12
                ],
                'output' => [
                    'message' => ['The description field is required.']
                ]
            ],
            // test #3
            [
                'input'  => [
                    'name'        => 'name',
                    'description' => 'description',
                ],
                'output' => [
                    'message' => ['The price field is required.']
                ]
            ],
            // test #4
            [
                'input'  => [
                    'name'        => 'name',
                    'description' => 'description',
                    'price'       => -1
                ],
                'output' => [
                    'message' => ['The price format is invalid.']
                ]
            ],
            // test #5
            [
                'input'  => [
                    'name'        => 'name',
                    'description' => 'description',
                    'price'       => 12.045
                ],
                'output' => [
                    'message' => ['The price format is invalid.']
                ]
            ],
            // test #6
            [
                'input'  => [
                    'name'        => 'namenamenamenamenamenamenamenamenamenamenamenamenamenamenamenamenamenamenamenamenamenamenamenamenamenamenamenamenamenamenamename',
                    'description' => 'description',
                    'price'       => 12
                ],
                'output' => [
                    'message' => ['The name may not be greater than 50 characters.']
                ]
            ],
            // test #6
            [
                'input'  => [
                    'name'        => 'namename',
                    'description' => 'descriptiondescriptiondescriptiondescriptiondescriptiondescriptiondescriptiondescriptiondescriptiondescriptiondescriptiondescriptiondescriptiondescriptiondescriptiondescriptiondescriptiondescriptiondescriptiondescriptiondescriptiondescriptiondescriptiondescriptiondescriptiondescription',
                    'price'       => 12
                ],
                'output' => [
                    'message' => ['The description may not be greater than 200 characters.']
                ]
            ],
        ];
    }
}
