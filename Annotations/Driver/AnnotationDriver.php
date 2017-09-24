<?php

namespace RA\RequestValidatorBundle\Annotations\Driver;

use Doctrine\Common\Annotations\Reader as AnnotationReaderInterface; //AnnotationReader
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpFoundation\JsonResponse;

use RA\RequestValidatorBundle\RequestValidator\RequestValidator;
use RA\RequestValidatorBundle\Annotations\ValidateQueryAnnotation;
use RA\RequestValidatorBundle\Annotations\ValidateQuery;
use RA\RequestValidatorBundle\Annotations\ValidateRequest;
use RA\RequestValidatorBundle\Annotations\ValidateAny;
use RA\RequestValidatorBundle\RequestValidator\ValidationException as RequestValidatorValidationException;

/**
* AnnotationDriver
*/
class AnnotationDriver
{
    /**
    *
    * @var AnnotationReaderInterface $reader
    */
    private $reader;
    /**
    *
    * @var RequestValidator $validator
    */
    private $validator;

    function __construct(AnnotationReaderInterface $reader, RequestValidator $validator)
    {
        $this->reader = $reader;
        $this->validator = $validator;
    }

    public function onKernelController(FilterControllerEvent $event)
    {
        if (!is_array($infos = $event->getController())) { //return if no controller
            return;
        }
        $controller = $infos[0];

        $object = new \ReflectionObject($controller);// get controller
        $method = $object->getMethod($infos[1]);// get method

        $newController = function(\Exception $e) {
            return new JsonResponse($e->getErrors(), $e->getCode());
        };

        foreach ($this->reader->getMethodAnnotations($method) as $annotation) { //Start of annotations reading

            try {
                if($annotation instanceOf ValidateQuery){
                    $this->validator->validateQuery($annotation->configuration,$annotation->constraintsClass);
                }
                if($annotation instanceOf ValidateRequest){
                    $this->validator->validateRequest($annotation->configuration,$annotation->constraintsClass);
                }
                if($annotation instanceOf ValidateAny){
                    $this->validator->validateAny($annotation->configuration,$annotation->constraintsClass);
                }
            } catch (RequestValidatorValidationException $e) {
                $event->setController(function() use ($e) {
                    return new JsonResponse($e->getErrors(), $e->getCode());
                });
            }

        }
    }
}
