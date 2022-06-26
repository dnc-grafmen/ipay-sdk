<?php

declare(strict_types=1);

namespace IPaySdk;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use IPaySdk\DTO\DataDTOInterface;
use IPaySdk\Factory\ConverterServiceFactory;
use IPaySdk\Factory\PaymentFactoryInterface;
use IPaySdk\Response\ApiResponseInterface;
use IPaySdk\Service\ResponseTransformService;

final class IPayClient
{
    public function __construct(
        private int $merchantId,
        private string $signKey,
        private ClientInterface $guzzleClient,
        private string $apiEndpoint
    ) {}

    public function send(PaymentFactoryInterface $paymentFactory, DataDTOInterface $data): ApiResponseInterface
    {
        $model = $paymentFactory->create($this->merchantId, $this->signKey, $data);

        $serviceFactory = new ConverterServiceFactory();
        $serviceXml = $serviceFactory->make(ConverterServiceFactory::TYPE_XML);
        $data = $serviceXml->convertModel($model);

        try {
            $response = $this->guzzleClient->request(Constants::HTTP_METHOD, $this->apiEndpoint, [
                RequestOptions::FORM_PARAMS => [
                    'data' => $data,
                ]
            ]);

            return (new ResponseTransformService())->convertResponse($response, $paymentFactory->getResponseType());
        } catch (GuzzleException $exception) {
            throw $exception;
        }
    }
}
