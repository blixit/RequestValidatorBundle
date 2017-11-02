<?php

namespace RA\RequestValidatorBundle\RequestValidator;

use Symfony\Component\HttpFoundation\Request;

/**
 * ValidatedRequest
 */
class ValidatedRequest
{
    const GETTER = 'g';
    const SETTER = 's';

    private $allowedFields = [];

    function __construct(array $fields)
    {
        $this->allowedFields = $fields;
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
            throw new \Exception(sprintf("Unknown function %s.", $name), 1);
        }

        if($methodType == self::GETTER){
            return $this->allowedFields[$field];
        }
        elseif($methodType == self::SETTER){
            $this->allowedFields[$field] = $arguments[0];
            return $this;
        }
    }


}
