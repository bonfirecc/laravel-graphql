<?php
/**
 * Created by PhpStorm.
 * User: lespinosa
 * Date: 10/15/18
 * Time: 10:55 PM
 */

namespace BonfireCC\GraphQL\Support;


use GraphQL\Language\AST\StringValueNode;
use GraphQL\Type\Definition\ScalarType;
use GraphQL\Utils\Utils;
use Illuminate\Support\Carbon;

class DateTime extends ScalarType
{
    private static $_instance;
    /**
     * @var string
     */
    public $name = 'DateTime';

    protected $format;

    protected $tz;

    /**
     * @var string
     */
    public $description = 'A DateTime format Y-m-d H:i:s';

    public function __construct()
    {
        Utils::invariant($this->name, 'Type must be named.');
        $this->tz = \Config::get('app.timezone');
        $this->format = 'Y-m-d H:i:s';
    }

    static public function type()
    {
        return self::$_instance ?: (self::$_instance = new self());
    }

    /**
     * Value sent to the client
     * @param mixed $value
     * @return mixed|string
     * @throws \Exception
     */
    public function serialize($value)
    {


        if ($value instanceof Carbon) {
            return $value->setTimezone($this->tz)
                ->format($this->format);
        }
        if (\is_string($value)) {
            return (new Carbon($value, $this->tz))->format($this->format);
        }
        if (\is_object($value)) {
            return Carbon::parse($value, $this->tz)->format($this->format);
        }
        throw new \Exception(sprintf('Failed to serialize %s, expected value to be an instance of \DateTimeInterface',
            $this->name));
    }

    /**
     * value from the client
     * @param mixed $value
     * @return mixed|string
     * @throws \Exception
     */
    public function parseValue($value)
    {
        if (\is_string($value)) {
            return (new Carbon($value))->format($this->format);
        }
        throw new \Exception(sprintf('Failed to parse value for %s, expected value to be a string, got %s',
            $this->name, \gettype($value)));
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
