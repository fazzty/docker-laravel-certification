<?php

// return [
//   'endpoint' => env('DYNAMODB_ENDPOINT', 'http://dynamodb:8000'),
//   'region' => env('AWS_DEFAULT_REGION', 'us-west-2'),
//   'version' => 'latest',
//   'credentials' => [
//       'key' => env('AWS_ACCESS_KEY_ID', 'dummy_key'),
//       'secret' => env('AWS_SECRET_ACCESS_KEY', 'dummy_secret'),
//   ],
// ];

return [
  'endpoint' => env('DYNAMODB_ENDPOINT', 'http://dynamodb:8000'),
  'region' => env('AWS_DEFAULT_REGION', 'us-west-2'),
  'version' => 'latest',
  'credentials' => [
      'key' => 'dummy',
      'secret' => 'dummy',
  ],
];