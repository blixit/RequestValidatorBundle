<?php

namespace RA\RequestValidatorBundle\Sample;

use RA\RequestValidatorBundle\RequestValidator\Constraints as RequestValidatorConstraints;

use Symfony\Component\Validator\Constraints\Collection as ConstraintsCollection;
use Symfony\Component\Validator\Constraints\Optional;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotEqualTo;

/**
 *
 */
class CustomConstraints extends RequestValidatorConstraints
{

    protected function configure() : array
    {
        return [
            'topics'    => [
                'query' => new ConstraintsCollection([
                    'method'    => new NotBlank(),
                    'tmp'       => new Optional ([new NotEqualTo(15)])
                ]),
                'request'    => new ConstraintsCollection([]),
            ],
            'messages'   => new ConstraintsCollection([
                'method'    => new NotBlank(),
                'tmp'    => new NotBlank(),
                'test'    => new NotBlank(),
            ])
        ];
    }



}
