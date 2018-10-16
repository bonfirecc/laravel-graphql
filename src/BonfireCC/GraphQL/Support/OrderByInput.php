<?php

namespace BonfireCC\GraphQL\Support;

use GraphQL\Type\Definition\EnumType as EnumObjectType;

class OrderByInput extends EnumObjectType
{
    protected $attributes = [
        'name' => 'OrderByEnum',
        'description' => 'An enum'
    ];
    protected $typeName;

    public function __construct($typeName, $fields)
    {
        $this->typeName = $typeName;
        parent::__construct([
            'name' => $typeName . 'OrderByEnum',
            'description' => 'An enum',
            'values' => $this->buildEnum($fields),
        ]);
    }

    protected function buildEnum($fields)
    {
        $wheres = [];
        foreach ($fields as $fieldName => $field) {
            $wheres[$fieldName . '_ASC'] = $fieldName . '_ASC';
            $wheres[$fieldName . '_DESC'] = $fieldName . '_DESC';
        }
        return $wheres;
    }
}
