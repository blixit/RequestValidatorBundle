<?php

namespace RA\RequestValidatorBundle\RequestValidator;

use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints\Collection as ConstraintsCollection;

use RA\RequestValidatorBundle\RequestValidator\ValidationException as RequestValidatorValidationException;

use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Constraints
 */
class Constraints
{

    const ANY       = 'any';
    const QUERY     = 'query';
    const REQUEST   = 'request';
    /**
     * @var array $configurations
     */
    private $configurations;

    function __construct() {

        $this->configurations   = $this->configure();

    }

    protected function configure() : array {
        return [

        ];
    }

    /**
     * Returns configuration
     * @param  string                 $configuration [description]
     * @return ConstraintsCollection                [description]
     */
    public function getConfiguration(string $configuration, string $type) : ConstraintsCollection {
        if( ! array_key_exists($configuration, $this->configurations)){
            throw new RequestValidatorValidationException(["Configuration '$configuration' not found"], 500);
        }

        $constraints = $this->configurations[$configuration];

        if($type == self::ANY && $constraints instanceOf ConstraintsCollection){
            return $constraints;
        }

        if( ! is_array($constraints)){
            throw new RequestValidatorValidationException(["No configuration found. If you are using validateQuery() or validateRequest() please define a configuration for $configuration"."[".self::QUERY."] or/and $configuration"."[".self::REQUEST."]"], 500);
        }

        return $this->getConstraintsByType($configuration, $type, $constraints);
    }

    private function getConstraintsByType(string $configuration, string $type, array $constraints) : ConstraintsCollection{
        if( ! array_key_exists($type, $constraints)){
            throw new RequestValidatorValidationException(["Configuration ".$configuration."_$type not found"], 500);
        }
        return $constraints[$type];
    }



}
