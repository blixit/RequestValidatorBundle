<?php

namespace RA\RequestValidatorBundle\Sample;

use RA\RequestValidatorBundle\RequestValidator\Constraints as RequestValidatorConstraints;

use Symfony\Component\Validator\Constraints\Collection as ConstraintsCollection;
use Symfony\Component\Validator\Constraints\Optional;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotEqualTo;

/**
 *  TopicsConstraints
 *
 * A special class for topic constraints
 */
class TopicsConstraints extends RequestValidatorConstraints
{

    protected function configure() : array
    {
        return [
            'topics'    => [
                'query' => new ConstraintsCollection([
                    'method'    => new Optional ([new NotBlank()]),
                    'tmp'       => new Optional ([new NotEqualTo(15)])
                ]),
                'request'    => new ConstraintsCollection([]),
            ]
        ];
    }



}
