<?php 
namespace BonfireCC\GraphQL\Support;

use App\GraphQL\Scalars\Date;
use App\GraphQL\Scalars\DateTime;
use App\GraphQL\Scalars\Timestamp;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\Type as GraphQLType;
use BonfireCC\GraphQL\Support\Facades\GraphQL;

class InputType extends InputObjectType
{

    public function __construct($typeName, $fields)
    {
        $name = $typeName . 'Input';
        $config = [
            'name'  => $name,
            'description' => '',
            'fields' => $this->buildDataInput($fields)
        ];
        parent::__construct($config);
    }
    public function buildDataInput($fields, $typeName = '')
    {
        $data = [];
        foreach ($fields as $fieldName => $field) {
            switch ($field['type']) {
                case 'Int!':
                case 'Int':
                case '[Int!]':
                case '[Int]':
                    $data[$fieldName]['type'] = GraphQLType::int();
                    break;
                case 'Float!':
                case 'Float':
                case '[Float!]':
                case '[Float]':
                    $data[$fieldName]['type'] = GraphQLType::float();
                    break;
                case 'String!':
                case 'String':
                case '[String!]':
                case '[String]':
                    $data[$fieldName]['type'] = GraphQLType::string();
                    break;
                case 'DateTime!':
                case 'DateTime':
                case '[DateTime!]':
                case '[DateTime]':
                    $data[$fieldName]['type'] = DateTime::type();
                    break;
                    break;
                case 'Date!':
                case 'Date':
                case '[Date!]':
                case '[Date]':
                    $data[$fieldName]['type'] = Date::type();
                    break;
                case 'Timestamp!':
                case 'Timestamp':
                case '[Timestamp!]':
                case '[Timestamp]':
                    $data[$fieldName]['type'] = Timestamp::type();
                    break;
                case 'Boolean!':
                case 'Boolean':
                    $data[$fieldName]['type'] = GraphQLType::boolean();
                    break;
                case 'MessageCategoryEnum':
                    $data[$fieldName]['type'] = GraphQL::type('MessageCategoryEnum');
                    break;
                case 'MessageToTypeEnum':
                    $data[$fieldName]['type'] = GraphQL::type('MessageToTypeEnum');
                    break;
                case 'EventStatus':
                    $data[$fieldName]['type'] = GraphQL::type('EventStatus');
                    break;
                default:
                    $search = ['[', ']', '!'];
                    $replace = ['', '', ''];
                    $type = trim(str_replace($search, $replace, $field['type']));
                    $vType = GraphQL::type($type . 'Input');
                    if (in_array('[', $search)) {
                        $vType = Type::listOf(GraphQL::type($type . 'Input'));
                    }
                    $data[$fieldName]['type'] = $vType;
                    break;
            }
        }
        return $data;
    }
}