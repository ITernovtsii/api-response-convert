Response Convert Bundle
========================

About bundle
---------------------------
This bundle is a simple solution to convert models/DTO/Exceptions to JsonResponse


Installation
============

Step 1: Download the Bundle
---------------------------

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```bash

    $ composer require tandrewcl/api-response-convert
```

This command requires you to have Composer installed globally, as explained
in the [installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.


Step 2: Config and Usage
--------------------------

Thanks for Symfony flex Bundle is auto enabled in config/bundles.php

```php
...
use tandrewcl\ApiResponseConvertBundle\Converter\ResponseConverterInterface;
use tandrewcl\ApiResponseConvertBundle\Handler\ResponseHandler;
use tandrewcl\ApiResponseConvertBundle\Model\ConvertedResponseModel;
...

class FooConverter implements ResponseConverterInterface
{
    public static function getDefaultSupportedClassPriority(): int
    {
        return -245;
    }

    public function support(mixed $data): bool
    {
        return $data instanceof \Exception;
    }

    public static function getDefaultSupportedClassName(): string
    {
        return \Exception::class;
    }

    /**
     * @param \Exception $data
     */
    public function convert(mixed $data, ResponseHandler $responseHandler): ConvertedResponseModel
    {
        return new ConvertedResponseModel(
           message: $data->getMessage(), statusCode: Response::HTTP_INTERNAL_SERVER_ERROR
        );
    }
}
```
```php
...
use tandrewcl\ApiResponseConvertBundle\Handler\ResponseHandler;
use Symfony\Component\HttpFoundation\JsonResponse;
...

class FooController
{
    public function __construct(
        private readonly ResponseHandler $responseHandler
    )
    {
    }

    public function indexAction(): JsonResponse
    {
        ...

        return $this->responseHandler->generateResponse($data);
    }

```


