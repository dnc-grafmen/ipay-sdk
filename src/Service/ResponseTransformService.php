<?php

declare(strict_types=1);

namespace IPaySdk\Service;

use Doctrine\Common\Annotations\AnnotationReader;
use IPaySdk\Exceptions\PaymentException;
use IPaySdk\Response\ApiResponseInterface;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\NameConverter\MetadataAwareNameConverter;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\PropertyNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

class ResponseTransformService
{
    private SerializerInterface $serializer;

    public function __construct()
    {
        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
        $metadataAwareNameConverter = new MetadataAwareNameConverter($classMetadataFactory);
        $extractor = new PropertyInfoExtractor([], [new PhpDocExtractor(), new ReflectionExtractor()]);

        $encoders = [
            new XmlEncoder(),
        ];

        $normalizers = [
            new ObjectNormalizer(null, $metadataAwareNameConverter, null, $extractor),
            new PropertyNormalizer(),
            new GetSetMethodNormalizer(),
            new ArrayDenormalizer(),
        ];

        $this->serializer = new Serializer($normalizers, $encoders);
    }

    public function convertResponse(ResponseInterface $response, string $type): ApiResponseInterface
    {
        $data = $response->getBody()->getContents();

        if (
            $response->getStatusCode() >= 400 ||
            str_contains($data, 'empty request') ||
            // response from api can be have the "<error>...</error>"
            str_contains($data, '<error>')
        ) {
            throw new PaymentException(
                sprintf(
                    'Error from API: %s',
                    $data
                ),
                $response->getStatusCode()
            );
        }

        assert(array_key_exists(ApiResponseInterface::class, class_implements($type)));

        return $this->serializer->deserialize($data, $type, 'xml');
    }
}
