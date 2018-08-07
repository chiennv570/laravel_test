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
    public function testStoreProduct($input, $output)
    {
        // check the case not validate when create product
        $response = $this->json('POST', '/api/product', $input);

        $response->assertStatus(400)
                 ->assertExactJson($output);
    }

    /**
     * @dataProvider dataUpdateProvider
     *
     * @throws \Exception
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public function testUpdateProduct($input, $output)
    {
        // check the case not validate when update product
        $response = $this->json('PUT', '/api/product/1', $input);

        $response->assertStatus(400)
                 ->assertExactJson($output);
    }

    // check the case not validate when create product
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
            // test #7
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

    // check the case not validate when update product
    public function dataUpdateProvider()
    {
        return [
            // test #1
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
            // test #2
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
            // test #3
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
            // test #4
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
            // test #5
            [
                'input'  => [],
                'output' => [
                    'message' => [
                        'The name field is required when none of description / price are present.',
                        'The description field is required when none of name / price are present.',
                        'The price field is required when none of name / description are present.'
                    ]
                ]
            ],
        ];
    }
}
