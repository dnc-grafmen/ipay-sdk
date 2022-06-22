<?php

declare(strict_types=1);

namespace IPaySdk;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Request;
use IPaySdk\DTO\DataDTOInterface;
use IPaySdk\Factory\ConverterServiceFactory;
use IPaySdk\Factory\PaymentFactoryInterface;
use IPaySdk\Response\EntityInterface;
use IPaySdk\Service\ResponseTransformService;

final class IPayClient
{
    private ClientInterface $guzzleClient;

    public function __construct(
        private int $merchantId,
        private string $signKey,
        private string $apiEndpoint
    ) {
        $this->guzzleClient = new Client();
    }

    public function send(PaymentFactoryInterface $paymentFactory, DataDTOInterface $data): EntityInterface
    {
        $model = $paymentFactory->create($this->merchantId, $this->signKey, $data);

        $serviceFactory = new ConverterServiceFactory();
        $serviceXml = $serviceFactory->make(ConverterServiceFactory::TYPE_XML);
        $data = $serviceXml->convertModel($model);

        $request = new Request(Constants::HTTP_METHOD, $this->apiEndpoint, [], $data);

        try {
            $response = $this->guzzleClient->send($request);

            return (new ResponseTransformService())
                ->convertResponse($response);
        } catch (GuzzleException $exception) {
            throw $exception;
        }
    }
}
