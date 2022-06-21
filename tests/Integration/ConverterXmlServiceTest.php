<?php

declare(strict_types=1);

namespace Integration;

use IPaySdk\DTO\CreatePaymentDTO;
use IPaySdk\DTO\TransactionDTO;
use IPaySdk\DTO\TransactionDTOCollection;
use IPaySdk\DTO\UrlsDTO;
use IPaySdk\Factory\CreatePaymentFactory;
use IPaySdk\Generator\SaltGeneratorInterface;
use IPaySdk\Model\ModelInterface;
use IPaySdk\Service\ConverterServiceInterface;
use IPaySdk\Service\ConverterXmlService;
use PHPUnit\Framework\TestCase;

class ConverterXmlServiceTest extends TestCase
{
    private ConverterServiceInterface $converterService;

    protected function setUp(): void
    {
        $this->converterService = new ConverterXmlService();
    }

    /**
     * @dataProvider convertModelDataProvider
     *
     * @param ModelInterface $model
     * @param string         $expectedXmlString
     */
    public function testConvertModel(ModelInterface $model, string $expectedXmlString): void
    {
        self::assertXmlStringEqualsXmlString($expectedXmlString, $this->converterService->convertModel($model));
    }

    public function convertModelDataProvider(): iterable
    {
        return [
            [
                (new CreatePaymentFactory($this->createSaltGenerator()))
                    ->create(
                        2023,
                        'sign_key_secret',
                        new CreatePaymentDTO(
                            new UrlsDTO('http://www.example.com/ok/', 'http://www.example.com/fail/'),
                            (new TransactionDTOCollection())
                                ->addTransaction(
                                    new TransactionDTO(
                                        55,
                                        'UAH',
                                        'Покупка товара/услуги',
                                        ['dogovor' => 123456],
                                        4301,
                                    )
                                ),
                            24,
                            'ru',
                            ['ru' => 'название на русском', 'ua' => 'назва на українській', 'en' => 'english name']
                        )
                    ),
                '<?xml version="1.0" encoding="utf-8" standalone="yes"?>
<payment>
    <auth>
        <mch_id>2023</mch_id>
        <salt>cd4122c9e96209a83fd4ffdc4479471e0cc8c974</salt>
        <sign>a6e1f03c5b132b52ae8f1925a79f438824198f7e3a3ac3ccb5c2d570bb84119bacf97bf67b772515d710d3e2d1f3ba8c368c395fd7512bd72c4aca94886dc301</sign>
    </auth>
    <urls>
        <good>http://www.example.com/ok/</good>
        <bad>http://www.example.com/fail/</bad>
    </urls>
    <transactions>
        <transaction>
            <amount>55</amount>
            <currency>UAH</currency>
            <desc>Покупка товара/услуги</desc>
            <info>{"dogovor":123456}</info>
            <smch_id>4301</smch_id>
        </transaction>
    </transactions>
    <lifetime>24</lifetime>
    <lang>ru</lang>
    <trademark>{"ru":"название на русском","ua":"назва на українській","en":"english name"}</trademark>
</payment>',
            ],
        ];
    }

    protected function createSaltGenerator(): SaltGeneratorInterface
    {
        $stub = $this->createMock(SaltGeneratorInterface::class);
        $stub->method('getSalt')->willReturn(sha1('my_secret_word_for_salt'));

        return $stub;
    }
}
