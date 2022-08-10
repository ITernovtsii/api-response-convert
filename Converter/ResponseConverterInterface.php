<?php

namespace tandrewcl\ApiResponseConvertBundle\Converter;

use tandrewcl\ApiResponseConvertBundle\Handler\ResponseHandler;
use tandrewcl\ApiResponseConvertBundle\Model\ConvertedResponseModel;

interface ResponseConverterInterface
{
    public function support(mixed $data): bool;

    public function convert(mixed $data, ResponseHandler $responseHandler): ConvertedResponseModel;

}