<?php

namespace RA\RequestValidatorBundle\Annotations; 

/**
 * ValidateAnnotation
 */
class ValidateAnnotation
{
    /**
     *
     * @var string $configuration
     */
     public $configuration;

     /**
      *
      * @var string $constraintsClass
      */
     public $constraintsClass;



     public function __constructor( string $configuration, string $constraintsClass)
     {
         $this->configuration = $configuration;
         $this->constraintsClass = $constraintsClass;
     }


}
