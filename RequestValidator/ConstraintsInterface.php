<?php

namespace RA\RequestValidatorBundle\RequestValidator;

use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints\Collection as ConstraintsCollection;
//use Symfony\Component\Validator\ConstraintViolationListInterface;

use Symfony\Component\Validator\Constraints\NotBlank;

/**
 *
 */
interface ConstraintsInterface
{

    /**
     * Defines a configuration for the RequestValidator object
     * @return array an array containing the configuration in which each item is a collection of Constraints
     */
    function configure() : array ;

    /**
     * Returns the collection of constraints of the current RequestValidator object
     * @return ConstraintsCollection
     */
    function getConstraints() : ConstraintsCollection ;



}
