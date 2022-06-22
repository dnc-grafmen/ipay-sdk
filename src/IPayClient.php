<?php

declare(strict_types=1);

namespace IPaySdk;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Request;
use IPaySdk\DTO\DataDTOInterface;
use IPaySdk\Exceptions\PaymentException;
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

        $request = new Request(Constants::HTTP_METHOD, $this->apiEndpoint, [], $data);

        try {
            $response = $this->guzzleClient->send($request);

            if ($response->getStatusCode() >= 400) {
                throw new PaymentException(sprintf('Error from API: %s', $response->getBody()->getContents()), $response->getStatusCode());
            }

            return (new ResponseTransformService())->convertResponse($response, $paymentFactory->getResponseType());
        } catch (GuzzleException $exception) {
            throw $exception;
        }
    }
}
