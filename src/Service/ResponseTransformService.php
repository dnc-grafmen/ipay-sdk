<?php

declare(strict_types=1);

namespace IPaySdk\Service;

use IPaySdk\Entity\EntityInterface;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

use function PHPUnit\Framework\assertTrue;

class ResponseTransformService
{
    private SerializerInterface $serializer;

    public function __construct()
    {
        $encoders = [
            new XmlEncoder(),
        ];

        $normalizers = [
            new ObjectNormalizer(null, new CamelCaseToSnakeCaseNameConverter(), null, new ReflectionExtractor()),
            new GetSetMethodNormalizer(),
            new ArrayDenormalizer(),
            new DateTimeNormalizer(),
        ];

        $this->serializer = new Serializer($normalizers, $encoders);
    }

    public function convertResponse(ResponseInterface $response, string $type): EntityInterface
    {
        assertTrue(array_key_exists(EntityInterface::class, class_implements($type)));

        $data = $response->getBody()->getContents();

        return $this->serializer->deserialize($data, $type, 'xml');
    }
}
