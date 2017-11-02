<?php

namespace RA\RequestValidatorBundle\RequestValidator;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

use Symfony\Component\Validator\Constraints\Collection as ConstraintsCollection;
use Symfony\Component\Validator\Validator\ValidatorInterface;

use RA\RequestValidatorBundle\RequestValidator\Constraints as RequestValidatorConstraints;
use RA\RequestValidatorBundle\RequestValidator\ValidatedRequest;
use RA\RequestValidatorBundle\RequestValidator\ValidationException as RequestValidatorValidationException;
use RA\RequestValidatorBundle\RequestValidator\ConstraintsInterface as RequestValidatorConstraintsInterface;


/**
 * RequestValidator
 */
class RequestValidator
{
    const Query     = 'q';
    const Request   = 'r';
    const Any       = 'a';

    /**
     * @var ValidatorInterface $validator
     */
    private $validator ;

    /**
     * @var Request $request
     */
    private $request ;

    /**
     *
     * @param Request                   $request   [description]
     * @param ValidatorInterface        $validator [description]
     */
    function __construct(RequestStack $requestStack, ValidatorInterface $validator){

        $this->validator        = $validator;
        $this->request          = $requestStack->getCurrentRequest();

    }

    private function validate(string $configuration, string $constraintClassName, array $fields, string $type = RequestValidatorConstraints::ANY){

        $constraintClass= (new \ReflectionClass($constraintClassName))->newInstance();
        $constraints    = $constraintClass->getConfiguration($configuration,$type);

        $violationList  = $this->validator->validate($fields, $constraints);

        $errors         = [];
        foreach ($violationList as $violation){
            $field = preg_replace('/\[|\]/', "", $violation->getPropertyPath());
            $error = $violation->getMessage();
            $errors[$configuration.'_'.$type.'_'.$field] = $error;
        }

        if( ! empty($errors) ){
            throw new RequestValidatorValidationException($errors);
        }
    }

    public function validateQuery(string $configuration, string $constraintClassName = RequestValidatorConstraints::class){

        $queryFields    = $this->request->query->all();
        $this->validate($configuration, $constraintClassName, $queryFields, RequestValidatorConstraints::QUERY);
        return $queryFields;
    }

    public function validateRequest(string $configuration, string $constraintClassName = RequestValidatorConstraints::class){

        $requestFields  = $this->request->request->all();
        $this->validate($configuration, $constraintClassName, $requestFields, RequestValidatorConstraints::REQUEST);
        return $requestFields;
    }

    public function validateAny(string $configuration, string $constraintClassName = RequestValidatorConstraints::class){

        $queryFields    = $this->request->query->all();
        $requestFields  = $this->request->request->all();
        $fields = array_merge($queryFields, $requestFields);

        $this->validate($configuration, $constraintClassName, $fields, RequestValidatorConstraints::ANY);
        return $fields;
    }

    public function getValidatedRequest(string $validationKind, string $configuration, string $constraintClassName = RequestValidatorConstraints::class)
    {
        switch($validationKind){
            case RequestValidator::Query    : $type = 'query';   $fields = $this->validateQuery($configuration, $constraintClassName); break;
            case RequestValidator::Request  : $type = 'request'; $fields = $this->validateRequest($configuration, $constraintClassName); break;
            default                         : $type = 'any';     $fields = $this->validateAny($configuration, $constraintClassName); break;
        }

        $constraintClass= (new \ReflectionClass($constraintClassName))->newInstance();
        $constraints    = $constraintClass->getConfiguration($configuration, $type);
        $allowedFields = array_keys($constraints->fields);

        return new ValidatedRequest($allowedFields, $fields);
    }



}
