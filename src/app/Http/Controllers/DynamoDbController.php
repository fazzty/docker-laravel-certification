<?php

namespace App\Http\Controllers;

use Aws\DynamoDb\DynamoDbClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


// class DynamoDbController extends Controller
// {
//     public function index()
//     {
//         $client = new DynamoDbClient(config('dynamodb'));

//         try {
//             $result = $client->listTables();
//             return response()->json($result['TableNames']);
//         } catch (\Aws\DynamoDb\Exception\DynamoDbException $e) {
//             return response()->json(['error' => $e->getMessage()], 500);
//         }
//     }
// }

class DynamoDbController extends Controller
{
    public function index()
    {
        $client = new DynamoDbClient([
            'endpoint' => config('dynamodb.endpoint'),
            'region' => config('dynamodb.region'),
            'version' => config('dynamodb.version'),
            'credentials' => [
                'key' => 'dummy',
                'secret' => 'dummy',
            ],
            // 'debug' => true, // デバッグ情報を有効にする
        ]);

        try {
            $result = $client->listTables();
            return response()->json($result['TableNames']);
        } catch (\Aws\DynamoDb\Exception\DynamoDbException $e) {
            // Log::error($e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function createTable()
    {
        $client = new DynamoDbClient([
            'endpoint' => config('dynamodb.endpoint'),
            'region' => config('dynamodb.region'),
            'version' => config('dynamodb.version'),
            'credentials' => [
                'key' => 'dummy',
                'secret' => 'dummy',
            ],
        ]);

        $params = [
            'TableName' => 'MyTable',
            'KeySchema' => [
                [
                    'AttributeName' => 'id',
                    'KeyType' => 'HASH'  // Partition key
                ],
            ],
            'AttributeDefinitions' => [
                [
                    'AttributeName' => 'id',
                    'AttributeType' => 'S'
                ],
            ],
            'ProvisionedThroughput' => [
                'ReadCapacityUnits' => 10,
                'WriteCapacityUnits' => 10
            ]
        ];

        try {
            $result = $client->createTable($params);
            return response()->json($result);
        } catch (\Aws\DynamoDb\Exception\DynamoDbException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function putItem()
    {
        $client = new DynamoDbClient([
            'endpoint' => config('dynamodb.endpoint'),
            'region' => config('dynamodb.region'),
            'version' => config('dynamodb.version'),
            'credentials' => [
                'key' => 'dummy',
                'secret' => 'dummy',
            ],
        ]);

        // $params = [
        //     'TableName' => 'MyTable',
        //     'Item' => [
        //         'id' => ['S' => '123'],
        //         'name' => ['S' => 'Sample Name'],
        //     ]
        // ];

        $params = [
          'TableName' => 'MyTable',
          'Item' => [
              'id' => ['S' => '140'],
              'name' => ['S' => 'sasaki takashi'],
              'credit' => [
                  'M' => [
                      'card_type' => ['S' => 'visa'],
                      'auth_code' => ['S' => '1024']
                  ]
              ],
          ]
       ];

        try {
            $result = $client->putItem($params);
            return response()->json($result);
        } catch (\Aws\DynamoDb\Exception\DynamoDbException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function getItem()
    {
        $client = new DynamoDbClient([
            'endpoint' => config('dynamodb.endpoint'),
            'region' => config('dynamodb.region'),
            'version' => config('dynamodb.version'),
            'credentials' => [
                'key' => 'dummy',
                'secret' => 'dummy',
            ],
        ]);

        $params = [
            'TableName' => 'MyTable',
            'Key' => [
                'id' => ['S' => '140'],
            ]
        ];

        try {
            $result = $client->getItem($params);
            return response()->json($result['Item']);
        } catch (\Aws\DynamoDb\Exception\DynamoDbException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


}