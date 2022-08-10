<?php

namespace tandrewcl\ApiResponseConvertBundle\Handler;

use tandrewcl\ApiResponseConvertBundle\Converter\ResponseConverterInterface;
use tandrewcl\ApiResponseConvertBundle\Model\ConvertedResponseModel;
use Symfony\Component\HttpFoundation\{JsonResponse, Response};
use Symfony\Contracts\Translation\TranslatorInterface;
use Traversable;

class ResponseHandler
{
    /** @var ResponseConverterInterface[] */
    private array $responseProcessorsMap;

    /**
     * @param ResponseConverterInterface[]|Traversable $responseProcessors
     */
    public function __construct(
        protected readonly Traversable|array $responseProcessors,
        protected readonly ?TranslatorInterface $translator = null
    )
    {
        $this->responseProcessorsMap = iterator_to_array($responseProcessors);
    }

    public function convert(mixed $data): ConvertedResponseModel
    {
        if ($data === null) {
            return new ConvertedResponseModel();
        }

        $processor = $this->getProcessor($data);
        if ($processor) {
            return $processor->convert($data, $this);
        }

        return new ConvertedResponseModel(
            message: 'No response processor for ' . gettype($data), statusCode: Response::HTTP_INTERNAL_SERVER_ERROR
        );
    }

    public function generateResponse(mixed $data): JsonResponse
    {
        $convertedData = $this->convert($data);
        $responseData = [];
        if ($convertedData->data) {
            $responseData['data'] = $convertedData->data;
        }
        if ($convertedData->errorData) {
            $responseData['errorData'] = $convertedData->errorData;
        }
        if ($convertedData->message) {
            $responseData['message'] = $this->translator->trans($convertedData->message, $convertedData->messageParams);
        }

        if ($convertedData->mainLevelData) {
            $responseData = array_merge($responseData, $convertedData->mainLevelData);
        }

        return new JsonResponse($responseData, $convertedData->statusCode);
    }

    private function getProcessor(mixed $data): ?ResponseConverterInterface
    {
        if (is_object($data) && isset($this->responseProcessorsMap[$data::class])) {
            return $this->responseProcessorsMap[$data::class];
        }

        $processor = null;
        foreach ($this->responseProcessors as $responseProcessor) {
            if ($responseProcessor->support($data)) {
                $processor = $responseProcessor;
                break;
            }
        }

        return $processor;
    }
}