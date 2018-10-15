<?php

use GraphQL\Type\Definition\Type;
use BonfireCC\GraphQL\Support\Field;

class ExampleField extends Field
{
    protected $attributes = [
        'name' => 'example'
    ];

    public function type()
    {
        return Type::listOf(Type::string());
    }

    public function args()
    {
        return [
            'index' => [
                'name' => 'index',
                'type' => Type::int()
            ]
        ];
    }

    public function resolve($root, $args)
    {
        return ['test'];
    }
}
