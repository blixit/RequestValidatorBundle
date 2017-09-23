<?php

namespace RA\RequestValidatorBundle\RequestValidator;

/**
 * ValidationException
 */
class ValidationException extends \Exception
{
    const BAD_REQUEST = 400;

    /**
     *
     * @var array $errors
     */
    private $errors;

    /**
     * [__construct description]
     * @param string  $message  [description]
     * @param integer $code     [description]
     * @param [type]  $previous [description]
     */
    function __construct(array $errors = [], int $code = self::BAD_REQUEST, \Throwable $previous = NULL){

        $message = join(',', $errors);

        parent::__construct( $message, $code, $previous);
        $this->errors = $errors;
    }

    public function getErrors() : array {
        return $this->errors;
    }
}
