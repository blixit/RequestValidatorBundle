<?php

namespace RA\RequestValidatorBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;

use RA\RequestValidatorBundle\Sample\CustomConstraints;
use RA\RequestValidatorBundle\Sample\TopicsConstraints;
use RA\RequestValidatorBundle\RequestValidator\ValidationException as RequestValidatorValidationException;

class DefaultController extends Controller
{
    /**
     * @Route("/api/v1/request_validator")
     */
    public function defaultAction(Request $request)
    {
        $validator = $this->get('request_validator');


        try {
            $validator->validateRequest('topics', TopicsConstraints::class);
        } catch (RequestValidatorValidationException $e) {
            return new JsonResponse($e->getErrors(), $e->getCode());
        }

        try {
            $validator->validateQuery('topics', TopicsConstraints::class);
        } catch (RequestValidatorValidationException $e) {
            return new JsonResponse($e->getErrors(), $e->getCode());
        }


        try {
            $validator->validateAny('messages', CustomConstraints::class);
        } catch (RequestValidatorValidationException $e) {
            return new JsonResponse($e->getErrors(), $e->getCode());
        }

        return new Response();
    }
}
