<?php

namespace BonfireCC\GraphQL\Support;


use GraphQL\Language\AST\StringValueNode;
use GraphQL\Type\Definition\ScalarType;
use GraphQL\Utils\Utils;
use Illuminate\Support\Carbon;

class Date extends ScalarType
{
    private static $_instance;
    /**
     * @var string
     */
    public $name = 'Date';

    /**
     * @var string
     */
    public $description = 'A DateTime format Y-m-d ex: 0000-00-00';

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
     * @return Date
     */
    public function toType($value)
    {

        $tz = \Config::get('app.timezone');
        $format = 'Y-m-d';

        if ($value instanceof Carbon) {
            return $value->setTimezone($tz)
                ->format($this->format);
        }
        if (\is_string($value)) {
            return (new Carbon($value, $tz))->format($format);
        }
        if (\is_object($value)) {
            return Carbon::parse($value, $tz)->format($format);
        }
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
