<?php

namespace BonfireCC\GraphQL\Support;

class Query extends Field {

    protected $auth;

    public function authorize(array $args)
    {
        try {
            $this->auth = JWTAuth::parseToken()->authenticate();
        } catch (JWTException $e) {
            $this->auth = null;
            throw new JWTException('unauthorized');
        }
        return (boolean)$this->auth;
    }
    public function inferRulesFromType($type, $prefix, $resolutionArguments)
    {
        $rules = [];

        // if it is an array type, add an array validation component
        if ($type instanceof ListOfType) {
            $prefix = "{$prefix}.*";
        }

        // make sure we are dealing with the actual type
        if ($type instanceof WrappingType) {
            $type = $type->getWrappedType();
        }

        // if it is an input object type - the only type we care about here...
        if ($type instanceof InputObjectType) {

            // merge in the input type's rules
            if ($prefix != 'where') {
                $rules = array_merge($rules, $this->getInputTypeRules($type, $prefix, $resolutionArguments));
            }

        }

        // Ignore scalar types

        return $rules;
    }

    public function getInputTypeRules(InputObjectType $input, $prefix, $resolutionArguments)
    {
        $rules = [];

        foreach ($input->getFields() as $name => $field) {
            $key = "{$prefix}.{$name}";

            // get any explicitly set rules
            if (isset($field->rules)) {
                $rules[$key] = $this->resolveRules($field->rules, $resolutionArguments);
            }

            // then recursively call the parent method to see if this is an
            // input object, passing in the new prefix

            $rules = array_merge($rules, $this->inferRulesFromType($field->type, $key, $resolutionArguments));

        }

        return $rules;
    }

    protected function getResolver()
    {
        if (!method_exists($this, 'resolve')) {
            return null;
        }

        $resolver = [$this, 'resolve'];
        $authorize = [$this, 'authorize'];

        return function () use ($resolver, $authorize) {
            $arguments = func_get_args();

            // Get all given arguments
            if (!is_null($arguments[2]) && is_array($arguments[2])) {

                $arguments[1] = array_merge($arguments[1], $arguments[2]);
            }

            // Authorize
            if (call_user_func($authorize, $arguments[1]) != true) {
                throw new \Exception(json_encode($arguments));
                throw with(new AuthorizationError('Unauthorized'));
            }

            // Validate mutation arguments
            if (method_exists($this, 'getRules')) {
                $args = array_get($arguments, 1, []);
                $rules = call_user_func_array([$this, 'getRules'], [$args]);

                // if where isn't isset
                if (!isset($args['where'])) {
                    $arguments[1] = array_merge($arguments[1], [
                        'where' => []
                    ]);
                }

                if (sizeof($rules)) {

                    // allow our error messages to be customised
                    $messages = $this->validationErrorMessages($args);

                    $validator = Validator::make($args, $rules, $messages);

                    if ($validator->fails()) {
                        throw with(new ValidationError('validation'))->setValidator($validator);
                    }
                }
            }

            // Replace the context argument with 'selects and relations'
            // $arguments[1] is direct args given with the query
            // $arguments[2] is context (params given with the query)
            // $arguments[3] is ResolveInfo
            if (isset($arguments[3])) {
                $fields = new SelectFields($arguments[3], $this->type(), $arguments[1]);
                $arguments[2] = $fields;
            }


            return call_user_func_array($resolver, $arguments);
        };
    }

    public function booleanToString($values = [])
    {
        $arrays = [];

        foreach ($values as $key => $value) {
            if (is_bool($value)) {
                $arrays[$key] = $value ? "1" : "0";
            } elseif (is_array($value)) {
                foreach ($value as $k => $v) {
                    if (is_bool($v)) {
                        $arrays[$key][$k] = $v ? "1" : "0";
                    } else {
                        $arrays[$key][$k] = $v;
                    }
                }
            } else {
                $arrays[$key] = $value;
            }
        }
        return $arrays;
    }
}
