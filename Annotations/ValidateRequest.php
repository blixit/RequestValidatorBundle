<?php

namespace RA\RequestValidatorBundle\Annotations;

use Doctrine\Common\Annotations\Annotation;

use RA\RequestValidatorBundle\Annotations\ValidateAnnotation;

/**
 * ValidateRequest
 * @Annotation
 */
class ValidateRequest extends ValidateAnnotation
{
    public function __constructor( string $configuration, string $constraintsClass)
    {
        parent::__constructor($configuration, $constraintsClass);
    }
}
