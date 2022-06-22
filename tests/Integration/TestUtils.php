<?php

declare(strict_types=1);

namespace Test\Integration;

use IPaySdk\Generator\SaltGeneratorInterface;

trait TestUtils
{

    protected function createSaltGenerator(): SaltGeneratorInterface
    {
        $stub = $this->createMock(SaltGeneratorInterface::class);
        $stub->method('getSalt')->willReturn(sha1('my_secret_word_for_salt'));

        return $stub;
    }

    protected function readFileXmlPaymentRequest(string $filename): string
    {
        $path = sprintf('%s/files/request/%s', dirname(__DIR__), $filename);

        if (!file_exists($path)) {
            throw new \Exception(sprintf('The file %s does\'nt found', $path));
        }

        return file_get_contents($path);
    }

    protected function readFileXmlPaymentResponse(string $filename): string
    {
        $path = sprintf('%s/files/response/%s', dirname(__DIR__), $filename);

        if (!file_exists($path)) {
            throw new \Exception(sprintf('The file %s does\'nt found', $path));
        }

        return file_get_contents($path);
    }
}