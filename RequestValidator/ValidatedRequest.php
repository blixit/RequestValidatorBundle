<?php

namespace RA\RequestValidatorBundle\RequestValidator;

use Symfony\Component\HttpFoundation\Request;

/**
 * ValidatedRequest
 *
 * Provide getter/setter to each field
 */
class ValidatedRequest
{
    const GETTER = 'g';
    const SETTER = 's';

    private $allowedFields = [];
    private $fields = [];

    function __construct(array $allowedFields, array $fields)
    {
        $this->allowedFields = $allowedFields;
        $this->fields = $fields;
    }

    public function __call($name, $arguments = null)
    {
        $getRE = '/^get/';
        $setRE = '/^set/';

        if(preg_match($getRE, $name, $matches )){
            $field = lcfirst(preg_replace($getRE, "", $name ));
            return $this->resolveField($field, self::GETTER, $arguments);
        }elseif(preg_match($setRE, $name, $matches )){
            $field = lcfirst(preg_replace($setRE, "", $name ));
            return $this->resolveField($field, self::SETTER, $arguments);
        }
    }

    private function resolveField(string $field, string $methodType, $arguments){

        if( ! in_array($field, array_keys($this->allowedFields))){
            throw new \Exception(sprintf("The field %s is not allowed.", $field), 1);
        }

        if($methodType == self::GETTER){
            return isset($this->allowedFields[$field]) ? $this->allowedFields[$field] : null;
        }
        elseif($methodType == self::SETTER){
            $this->allowedFields[$field] = $arguments[0];
            return $this;
        }
    }


}
