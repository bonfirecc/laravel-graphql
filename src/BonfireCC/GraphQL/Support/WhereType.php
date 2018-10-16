<?php 
namespace BonfireCC\GraphQL\Support;

use App\GraphQL\Scalars\Date;
use App\GraphQL\Scalars\DateTime;
use App\GraphQL\Scalars\Timestamp;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type as GraphQLType;
use BonfireCC\GraphQL\Support\Facades\GraphQL;

class WhereType extends ObjectType
{

    public function __construct($typeName, $fields)
    {
        $name = $typeName . 'WhereInput';
        $config = [
            'name'  => $name,
            'fields' => $this->buildWhere($fields)
        ];
        parent::__construct($config);
    }
    public function buildWhere($fields, $typeName = '')
    {
        $wheres = [];
        if ($typeName != '') {
            $wheres['OR'] = [
                'description' => 'Logical OR on all given filters.',
                'type' => GraphQLType::listOf(GraphQL::type($typeName)),
            ];
            $wheres['AND'] = [
                'description' => 'Logical AND on all given filters.',
                'type' => GraphQLType::listOf(GraphQL::type($typeName)),
            ];
            $wheres['NOT'] = [
                'description' => 'Logical NOT on all given filters.',
                'type' => GraphQLType::listOf(GraphQL::type($typeName)),
            ];
        }
        foreach ($fields as $fieldName => $field) {

            switch ($field['type']) {
                case 'Int!':
                case 'Int':
                case '[Int!]':
                case '[Int]':
                    $wheres[$fieldName]['type'] = GraphQLType::int();
                    $wheres[$fieldName . '_not']['type'] = GraphQLType::int();
                    $wheres[$fieldName . '_contains']['type'] = GraphQLType::int();
                    $wheres[$fieldName . '_not_contains']['type'] = GraphQLType::int();
                    $wheres[$fieldName . '_in']['type'] = GraphQLType::listOf(GraphQLType::nonNull(GraphQLType::int()));
                    $wheres[$fieldName . '_not_in']['type'] = GraphQLType::listOf(GraphQLType::nonNull(GraphQLType::int()));
                    $wheres[$fieldName . '_lt']['type'] = GraphQLType::int();
                    $wheres[$fieldName . '_lte']['type'] = GraphQLType::int();
                    $wheres[$fieldName . '_gt']['type'] = GraphQLType::int();
                    $wheres[$fieldName . '_gte']['type'] = GraphQLType::int();
                    $wheres[$fieldName . '_between']['type'] = GraphQLType::listOf(GraphQLType::nonNull(GraphQLType::int()));
                    $wheres[$fieldName . '_not_between']['type'] = GraphQLType::listOf(GraphQLType::nonNull(GraphQLType::int()));
                    break;
                case 'Float!':
                case 'Float':
                case '[Float!]':
                case '[Float]':
                    $wheres[$fieldName]['type'] = GraphQLType::float();
                    $wheres[$fieldName . '_not']['type'] = GraphQLType::float();
                    $wheres[$fieldName . '_contains']['type'] = GraphQLType::float();
                    $wheres[$fieldName . '_not_contains']['type'] = GraphQLType::float();
                    $wheres[$fieldName . '_in']['type'] = GraphQLType::listOf(GraphQLType::nonNull(GraphQLType::float()));
                    $wheres[$fieldName . '_not_in']['type'] = GraphQLType::listOf(GraphQLType::nonNull(GraphQLType::float()));
                    $wheres[$fieldName . '_lt']['type'] = GraphQLType::float();
                    $wheres[$fieldName . '_lte']['type'] = GraphQLType::float();
                    $wheres[$fieldName . '_gt']['type'] = GraphQLType::float();
                    $wheres[$fieldName . '_gte']['type'] = GraphQLType::float();
                    $wheres[$fieldName . '_between']['type'] = GraphQLType::listOf(GraphQLType::nonNull(GraphQLType::float()));
                    $wheres[$fieldName . '_not_between']['type'] = GraphQLType::listOf(GraphQLType::nonNull(GraphQLType::float()));
                    break;
                case 'String!':
                case 'String':
                case '[String!]':
                case '[String]':
                    $wheres[$fieldName]['type'] = GraphQLType::string();
                    $wheres[$fieldName . '_not']['type'] = GraphQLType::string();
                    $wheres[$fieldName . '_contains']['type'] = GraphQLType::string();
                    $wheres[$fieldName . '_not_contains']['type'] = GraphQLType::string();
                    $wheres[$fieldName . '_starts_with']['type'] = GraphQLType::string();
                    $wheres[$fieldName . '_not_starts_with']['type'] = GraphQLType::string();
                    $wheres[$fieldName . '_ends_with']['type'] = GraphQLType::string();
                    $wheres[$fieldName . '_not_ends_with']['type'] = GraphQLType::string();
                    $wheres[$fieldName . '_in']['type'] = GraphQLType::listOf(GraphQLType::nonNull(GraphQLType::string()));
                    $wheres[$fieldName . '_not_in']['type'] = GraphQLType::listOf(GraphQLType::nonNull(GraphQLType::string()));
                    break;
                case 'Timestamp!':
                case 'Timestamp':
                case '[Timestamp!]':
                case '[Timestamp]':
                    $wheres[$fieldName]['type'] = Timestamp::type();
                    $wheres[$fieldName . '_lt']['type'] = Timestamp::type();
                    $wheres[$fieldName . '_lte']['type'] = Timestamp::type();
                    $wheres[$fieldName . '_gt']['type'] = Timestamp::type();
                    $wheres[$fieldName . '_gte']['type'] = Timestamp::type();
                    $wheres[$fieldName . '_in']['type'] = GraphQLType::listOf(GraphQLType::nonNull(Timestamp::type()));
                    $wheres[$fieldName . '_not_in']['type'] = GraphQLType::listOf(GraphQLType::nonNull(Timestamp::type()));
                    $wheres[$fieldName . '_lt']['type'] = Timestamp::type();
                    $wheres[$fieldName . '_lte']['type'] = Timestamp::type();
                    $wheres[$fieldName . '_gt']['type'] = Timestamp::type();
                    $wheres[$fieldName . '_gte']['type'] = Timestamp::type();
                    $wheres[$fieldName . '_between']['type'] = GraphQLType::listOf(GraphQLType::nonNull(Timestamp::type()));
                    $wheres[$fieldName . '_not_between']['type'] = GraphQLType::listOf(GraphQLType::nonNull(Timestamp::type()));
                case 'DateTime!':
                case 'DateTime':
                case '[DateTime!]':
                case '[DateTime]':
                    $wheres[$fieldName]['type'] = DateTime::type();
                    $wheres[$fieldName . '_lt']['type'] = DateTime::type();
                    $wheres[$fieldName . '_lte']['type'] = DateTime::type();
                    $wheres[$fieldName . '_gt']['type'] = DateTime::type();
                    $wheres[$fieldName . '_gte']['type'] = DateTime::type();
                    $wheres[$fieldName . '_in']['type'] = GraphQLType::listOf(GraphQLType::nonNull(DateTime::type()));
                    $wheres[$fieldName . '_not_in']['type'] = GraphQLType::listOf(GraphQLType::nonNull(DateTime::type()));
                    $wheres[$fieldName . '_lt']['type'] = DateTime::type();
                    $wheres[$fieldName . '_lte']['type'] = DateTime::type();
                    $wheres[$fieldName . '_gt']['type'] = DateTime::type();
                    $wheres[$fieldName . '_gte']['type'] = DateTime::type();
                    $wheres[$fieldName . '_between']['type'] = GraphQLType::listOf(GraphQLType::nonNull(DateTime::type()));
                    $wheres[$fieldName . '_not_between']['type'] = GraphQLType::listOf(GraphQLType::nonNull(DateTime::type()));
                    break;
                case 'Date!':
                case 'Date':
                case '[Date!]':
                case '[Date]':
                    $wheres[$fieldName]['type'] = Date::type();
                    $wheres[$fieldName . '_lt']['type'] = Date::type();
                    $wheres[$fieldName . '_lte']['type'] = Date::type();
                    $wheres[$fieldName . '_gt']['type'] = Date::type();
                    $wheres[$fieldName . '_gte']['type'] = Date::type();
                    $wheres[$fieldName . '_in']['type'] = GraphQLType::listOf(GraphQLType::nonNull(Date::type()));
                    $wheres[$fieldName . '_not_in']['type'] = GraphQLType::listOf(GraphQLType::nonNull(Date::type()));
                    $wheres[$fieldName . '_lt']['type'] = Date::type();
                    $wheres[$fieldName . '_lte']['type'] = Date::type();
                    $wheres[$fieldName . '_gt']['type'] = Date::type();
                    $wheres[$fieldName . '_gte']['type'] = Date::type();
                    $wheres[$fieldName . '_between']['type'] = GraphQLType::listOf(GraphQLType::nonNull(Date::type()));
                    $wheres[$fieldName . '_not_between']['type'] = GraphQLType::listOf(GraphQLType::nonNull(Date::type()));
                    break;
                case 'Boolean!':
                case 'Boolean':
                    $wheres[$fieldName]['type'] = GraphQLType::boolean();
                    $wheres[$fieldName . '_not']['type'] = GraphQLType::boolean();
                    break;
                case 'MessageCategoryEnum':
                    $wheres[$fieldName]['type'] = GraphQL::type('MessageCategoryEnum');
                    break;
                case 'MessageToTypeEnum':
                    $wheres[$fieldName]['type'] = GraphQL::type('MessageToTypeEnum');
                    break;
                case 'EventStatus':
                    $wheres[$fieldName]['type'] = GraphQL::type('EventStatus');
                    break;
                case 'AlertCategoryTypeEnum':
                    $wheres[$fieldName]['type'] = GraphQL::type('AlertCategoryTypeEnum');
                    break;

                default:
                    $search = ['[', ']', '!'];
                    $replace = ['', '', ''];
                    $type = trim(str_replace($search, $replace, $field['type']));
                    $vType = GraphQL::type($type . 'WhereInput');

                    $wheres[$fieldName]['type'] = $vType;
                    break;

            }

        }
        return $wheres;
    }
}