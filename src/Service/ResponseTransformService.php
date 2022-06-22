<?php

declare(strict_types=1);

namespace IPaySdk\Service;

use Doctrine\Common\Annotations\AnnotationReader;
use IPaySdk\Response\EntityInterface;
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

use function PHPUnit\Framework\assertTrue;

class ResponseTransformService
{
    private SerializerInterface $serializer;

    public function __construct()
    {
        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));

        $metadataAwareNameConverter = new MetadataAwareNameConverter($classMetadataFactory);

        $encoders = [
            new XmlEncoder(),
        ];

        $extractor = new PropertyInfoExtractor([], [new PhpDocExtractor(), new ReflectionExtractor()]);

        $normalizers = [
            new ObjectNormalizer(null, $metadataAwareNameConverter, null, $extractor),
            new PropertyNormalizer(),
            new GetSetMethodNormalizer(),
            new ArrayDenormalizer(),
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
