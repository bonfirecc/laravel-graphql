<?php

namespace BonfireCC\GraphQL\Support;

use GraphQL\Type\Definition\ObjectType;
use BonfireCC\GraphQL\Support\Facades\GraphQL;

class EdgeType extends ObjectType
{
    protected $attributes = [
        'name' => 'Edge',
        'description' => 'A list of edges.'
    ];

    public function __construct($typeName)
    {
        parent::__construct([
            'name' => $typeName . 'Edge',
            'description' => 'The item at the end of the edge.',
            'fields' => [
                'node' => [
                    'type' => GraphQL::type($typeName),
                    'resolve' => function ($data) {
                       return $data;
                    },
                ]
            ]
        ]);
    }
}
