RequestValidatorBundle
==============

Step 1: Download the Bundle
---------------------------


Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```console
$ composer require relief_applications/symfony-request-validator-bundle @dev
```

This command requires you to have Composer installed globally, as explained
in the [installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

Step 2: Enable the Bundle
-------------------------

Then, enable the bundle by adding it to the list of registered bundles
in the `app/AppKernel.php` file of your project:

```php
<?php
// app/AppKernel.php

// ...
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            // ...

            new RA\RequestValidatorBundle\RARequestValidatorBundle(),
        );
    }
}
```


Step 3: Configuration
-------------------------

```yml
# app/config/services.yml according to your project path
imports:
    - { resource: "@RARequestValidatorBundle/Resources/config/services.yml" }
```


Step 4: Default routing
-------------------------
To test our default controller
```yml
# app/config/routing.yml according to your project path
RequestValidatorBundle:
    resource: "@RARequestValidatorBundle/Resources/config/routing.yml"
```

Step 5: Usage
-------------------------

##### 1. Define a custom constraints class

this class should extend **RA\RequestValidatorBundle\RequestValidator\Constraints**. The list of constraints is available here https://symfony.com/doc/current/reference/constraints.html

```php
<?php
namespace RequestValidatorBundle\Custom;

use RA\RequestValidatorBundle\RequestValidator\Constraints as RequestValidatorConstraints;

use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotEqualTo;
use Symfony\Component\Validator\Constraints\Optional;
use Symfony\Component\Validator\Constraints\Type;

/**
 *  TopicsConstraints
 *
 * A special class for topic constraints
 */
class TopicsConstraints extends RequestValidatorConstraints
{

    protected function configure() : array
    {
        //Reusable rules can be store into variables starting by 'common'
        $commonCategoryId = new Optional ([
            new Type('numeric'),
            new GreaterThan(0)
        ]);
        $commonNotBlank = new NotBlank();

        return [
            'topics'    => [
                // rules for URI fields
                'query'     => new Collection([
                    'method'            => new Optional ([new Choice(['by'])]),
                    'category_id'       => $commonCategoryId
                ]),
                // rules for POSTED fields
                'request'    => new Collection([
                    'category_id'       => $commonCategoryId,
                    'title'             => $commonNotBlank,
                    'description'       => $commonNotBlank,
                    'message'           => $commonNotBlank
                ]),
            ],
            // rules for ANY fields
            'topics2'    => new Collection([
                'category_id'       => $commonCategoryId,
                'title'             => $commonNotBlank,
                'description'       => $commonNotBlank,
                'message'           => $commonNotBlank
            ]),
        ];
    }

}


```


##### 2. Using annotations

```php
<?php

//...
use RequestValidatorBundle\Custom\TopicsConstraints; //our custom constraints class
use RA\RequestValidatorBundle\Annotations\ValidateQuery;
use RA\RequestValidatorBundle\Annotations\ValidateRequest;


class TopicController extends Controller
{
    /**
    * @Get("/", name="get_all_topics")
    * @ValidateQuery( configuration="topics", constraintsClass=TopicsConstraints::class)
    * Get a table of all the topics
    */
    public function getAllAction(Request $request){
        $categoryId     = $request->get('category_id');
        //...
    }

    /**
    * @Post("/", name="create_topic")
    * @ValidateRequest( configuration="topics", constraintsClass=TopicsConstraints::class)
    * Get a table of all the topics
    */
    public function createAction(Request $request){
        $categoryId         = $request->request->get('category_id');
        $title              = $request->request->get('title');
        $description        = $request->request->get('description');
        $messageContent     = $request->request->get('message');
        //...
    }

```

##### 3. Using functions

```php
<?php

//...
use RequestValidatorBundle\Custom\TopicsConstraints; //our custom constraints class
use RA\RequestValidatorBundle\RequestValidator\ValidationException as RequestValidatorValidationException;


class TopicController extends Controller
{
    /**
    * @Get("/", name="get_all_topics")
    * Get a table of all the topics
    */
    public function getAllAction(Request $request){
        try {
            $this->validator->validateQuery('topics',TopicsConstraints::class);
        } catch (RequestValidatorValidationException $e) {
            return new JsonResponse($e->getErrors(), $e->getCode());
        }

        $categoryId     = $request->get('category_id');
    }

    /**
    * @Post("/", name="create_topic")
    * Get a table of all the topics
    */
    public function createAction(Request $request){
        try {
            $this->validator->validateRequest('topics',TopicsConstraints::class);
        } catch (RequestValidatorValidationException $e) {
            return new JsonResponse($e->getErrors(), $e->getCode());
        }

        $categoryId         = $request->request->get('category_id');
        $title              = $request->request->get('title');
        $description        = $request->request->get('description');
        $messageContent     = $request->request->get('message');
    }

```

##### 4. Using ValidateAny

This method check both, URI and posted parameters. When using ValidateAny, the configuration defined into the constraints class can only contain a constraints collection. Here we use **topics2**. ValidateAny can also be used as a function in the controller body.
```php
<?php

//...
use RequestValidatorBundle\Custom\TopicsConstraints; //our custom constraints class
use RA\RequestValidatorBundle\Annotations\ValidateAny;


class TopicController extends Controller
{
    /**
    * @Get("/", name="get_all_topics")
    * @ValidateAny( configuration="topics2", constraintsClass=TopicsConstraints::class)
    * Get a table of all the topics
    */
    public function getAllAction(Request $request){
        $categoryId     = $request->get('category_id');
        //...
    }

    /**
    * @Post("/", name="create_topic")
    * @ValidateAny( configuration="topics2", constraintsClass=TopicsConstraints::class)
    * Get a table of all the topics
    */
    public function createAction(Request $request){
        $categoryId         = $request->request->get('category_id');
        $title              = $request->request->get('title');
        $description        = $request->request->get('description');
        $messageContent     = $request->request->get('message');
        //...
    }

```
