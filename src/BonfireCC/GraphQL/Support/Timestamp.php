<?php
/**
 * Created by PhpStorm.
 * User: lespinosa
 * Date: 10/15/18
 * Time: 10:53 PM
 */

namespace BonfireCC\GraphQL\Support;

use GraphQL\Language\AST\StringValueNode;
use GraphQL\Type\Definition\ScalarType;
use GraphQL\Utils\Utils;
use Illuminate\Support\Carbon;

class Timestamp extends ScalarType
{
    private static $_instance;
    /**
     * @var string
     */
    public $name = 'Timestamp';

    /**
     * @var string
     */
    public $description = 'A UNIX timestamp represented as an integer';

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
        return $this->toTimestamp($value);
    }

    /**
     * @return Timestamp
     */
    public function toTimestamp($value)
    {

        $tz = \Config::get('app.timezone');

        if (is_numeric($value)) {
            return $value;
        }
        if ($value instanceof Carbon) {
            return $value->setTimezone($tz)
                ->getTimestamp();
        }

        return (new Carbon($value, $tz))
            ->getTimestamp();
    }

    /**
     * @param mixed $value
     * @return string
     */
    public function parseValue($value)
    {
        return $this->toTimestamp($value);
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
