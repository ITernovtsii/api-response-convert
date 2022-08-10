<?php

namespace tandrewcl\ApiResponseConvertBundle\Model;

use Symfony\Component\HttpFoundation\Response;

class ConvertedResponseModel
{
    public function __construct(
        public ?array $data = null,
        public ?array $errorData = null,
        public ?string $message = null,
        public int $statusCode = Response::HTTP_OK,
        public array $messageParams = [],
        public ?array $mainLevelData = null,
    )
    {
    }
}