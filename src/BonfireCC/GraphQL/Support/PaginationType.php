<?php

namespace BonfireCC\GraphQL\Support;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type as GraphQLType;
use Illuminate\Pagination\LengthAwarePaginator;
use BonfireCC\GraphQL\Support\Facades\GraphQL;

class PaginationType extends ObjectType {

    public function __construct($typeName, $model = false)
    {
        $name = $typeName . 'Connection';

        $customPaginator = config('graphql.custom_paginators.' . $name, null);
        $customFields = $customPaginator ? $customPaginator::getPaginationFields() : [];

        $config = [
            'name'  => $name,
            'fields' => array_merge(
                $this->getPaginationFields(),
                $customFields,
                [
                  'edges' => [
                      'type' => GraphQLType::listOf(new EdgeType($typeName)),
                      'description' => 'A list of edges.',
                      'resolve' => function (LengthAwarePaginator $data) {
                          return $data;
                      },
                  ],
                ]
            )
        ];
        if($model) {
            $config['model'] = $model;
        }
        parent::__construct($config);
    }

    protected function getPaginationFields()
    {
        return [
            'totalCount' => [
                'type'          => GraphQLType::nonNull(GraphQLType::int()),
                'description'   => 'Number of total items selected by the query',
                'resolve'       => function(LengthAwarePaginator $data) { return $data->total(); },
                'selectable'    => false,
            ],
            'pageInfo' => [
                'type' => GraphQL::type('PageInfo'),
                'resolve' => function (LengthAwarePaginator $data) { return $data->getCollection(); },
            ],
        ];
    }

}
