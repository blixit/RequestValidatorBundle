<?php

namespace RA\RequestValidatorBundle\Annotations;

use Doctrine\Common\Annotations\Annotation;

use RA\RequestValidatorBundle\Annotations\ValidateAnnotation;

/**
 * ValidateQuery
 * @Annotation
 */
class ValidateQuery extends ValidateAnnotation
{
    public function __constructor( string $configuration, string $constraintsClass)
    {
        parent::__constructor($configuration, $constraintsClass);
    }
}
