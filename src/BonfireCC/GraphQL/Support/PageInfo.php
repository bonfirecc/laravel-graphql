<?php

namespace BonfireCC\GraphQL\Support;

use GraphQL\Type\Definition\Type;
use BonfireCC\GraphQL\Support\Type as BaseType;
use Illuminate\Pagination\LengthAwarePaginator;

class PageInfo extends BaseType
{
    protected $attributes = [
        'name' => 'PageInfo',
        'description' => 'A type'
    ];

    public function fields()
    {
        return [
            'count' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'Number of items returned per page',
                'resolve' => function (LengthAwarePaginator $data) {
                    return $data->perPage();
                },
            ],
            'page' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'Current page of the cursor',
                'resolve' => function (LengthAwarePaginator $data) {
                    return $data->currentPage();
                },
            ],
            'lastPage' => [
                'type' => Type::int(),
                'description' => 'Current page of the cursor',
                'resolve' => function (LengthAwarePaginator $data) {
                    return $data->lastPage();
                },
            ],
            'nextPage' => [
                'type' => Type::int(),
                'description' => 'Current page of the cursor',
                'resolve' => function (LengthAwarePaginator $data) {
                    if ($data->nextPageUrl() != null) {
                        $page = explode('=', $data->nextPageUrl());
                        return $page[1];
                    }

                    return null;
                },
            ],
            'prevPage' => [
                'type' => Type::int(),
                'description' => 'Current page of the cursor',
                'resolve' => function (LengthAwarePaginator $data) {
                    if ($data->previousPageUrl() != null) {
                        $page = explode('=', $data->previousPageUrl());
                        return $page[1];
                    }
                    return null;
                },
            ],
            'hasNextPage' => [
                'type' => Type::boolean(),
                'description' => 'has next page',
                'resolve' => function (LengthAwarePaginator $data) {
                    return $data->nextPageUrl() != null ? true : false;
                },

            ],
            'hasPreviousPage' => [
                'type' => Type::boolean(),
                'description' => 'Has Previous page',
                'resolve' => function (LengthAwarePaginator $data) {
                    return $data->previousPageUrl() != null ? true : false;
                },

            ],
        ];
    }
}
