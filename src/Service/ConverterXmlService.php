<?php

declare(strict_types=1);

namespace IPaySdk\Service;

use IPaySdk\Model\ModelInterface;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

class ConverterXmlService implements ConverterServiceInterface
{
    public function convertModel(ModelInterface $model): array|string
    {
        return '<?xml version="1.0" encoding="utf-8" standalone="yes"?>' .
            $this->convertModels($model);
    }

    private function convertModels(ModelInterface $model): string
    {
        if (count($model->getChildren())) {
            $value = '';

            foreach ($model->getChildren() as $child) {
                $value .= $this->convertModels($child);
            }
        } else {
            $value = $model->getValue();
        }

        return sprintf('<%1$s>%2$s</%1$s>', $model->getName(), $value);
    }
}
