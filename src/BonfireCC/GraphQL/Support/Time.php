<?php

namespace BonfireCC\GraphQL\Support;


use GraphQL\Language\AST\StringValueNode;
use GraphQL\Type\Definition\ScalarType;
use GraphQL\Utils\Utils;
use Illuminate\Support\Carbon;

class Time extends ScalarType
{
    private static $_instance;
    /**
     * @var string
     */
    public $name = 'DateTime';

    /**
     * @var string
     */
    public $description = 'A Time format H:i:s';

    public function __construct()
    {
        Utils::invariant($this->name, 'Type must be named.');
    }

    static public function type()
    {
        return self::$_instance ?: (self::$_instance = new self());
    }

    /**
     * @param mixed $value
     * @return mixed
     */
    public function serialize($value)
    {
        return $this->toType($value);
    }

    /**
     * @return Timestamp
     */
    public function toType($value)
    {

        $tz = \Config::get('app.timezone');
        $format = 'LT';

        if ($value instanceof Carbon) {
            return $value->setTimezone($tz)
                ->format($format);
        }

        return (new Carbon($value, $tz))->format($format);
    }

    /**
     * @param mixed $value
     * @return string
     */
    public function parseValue($value)
    {
        return $this->toType($value);
    }

    /**
     * ast value is always in string format
     * @param $ast
     * @return null|string
     */
    public function parseLiteral($ast)
    {
        if ($ast instanceof StringValueNode) {
            return $ast->value;
        }
    }

    protected function __clone()
    {
    }
}