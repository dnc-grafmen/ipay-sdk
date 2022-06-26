<?php

declare(strict_types=1);

namespace IPaySdk\Tests\Integration;

use IPaySdk\DTO\CompletionDTO;
use IPaySdk\DTO\CompletionWithTransactionDTO;
use IPaySdk\DTO\CreatePaymentDTO;
use IPaySdk\DTO\RefundDTO;
use IPaySdk\DTO\ReversalDTO;
use IPaySdk\DTO\StatusDTO;
use IPaySdk\DTO\TransactionDTO;
use IPaySdk\DTO\TransactionDTOCollection;
use IPaySdk\DTO\UrlsDTO;
use IPaySdk\Factory\CompletionFactory;
use IPaySdk\Factory\CompletionWithTransactionFactory;
use IPaySdk\Factory\CreatePaymentFactory;
use IPaySdk\Factory\RefundFactory;
use IPaySdk\Factory\ReversalFactory;
use IPaySdk\Factory\StatusFactory;
use IPaySdk\Model\ModelInterface;
use IPaySdk\Service\ConverterServiceInterface;
use IPaySdk\Service\ConverterXmlService;
use PHPUnit\Framework\TestCase;

class ConverterXmlServiceTest extends TestCase
{
    use TestUtils;

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
        yield 'Create payment request' => [
            (new CreatePaymentFactory($this->createSaltGenerator()))->create(
                new CreatePaymentDTO(
                    new UrlsDTO('http://www.example.com/ok/', 'http://www.example.com/fail/'),
                    (new TransactionDTOCollection())->addTransaction(
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
                ),
                2023,
                'sign_key_secret'
            ),
            $this->readFileXmlPaymentRequest('createPayment.xml'),
        ];

        yield 'Completion request' => [
            (new CompletionFactory($this->createSaltGenerator()))->create(
                new CompletionDTO(12345678),
                2023,
                'sign_key_secret'
            ),
            $this->readFileXmlPaymentRequest('completion.xml'),
        ];

        yield 'Completion with transaction request' => [
            (new CompletionWithTransactionFactory($this->createSaltGenerator()))->create(
                new CompletionWithTransactionDTO(
                    12345678,
                    new TransactionDTOCollection(
                        [
                            new TransactionDTO(
                                100,
                                'UAH',
                                'Покупка товара/услуги',
                                [
                                    "dogovor" => 12345
                                ],
                                4301
                            ),
                            new TransactionDTO(
                                200,
                                'UAH',
                                'Покупка услуги/товара',
                                [
                                    "dogovor" => 56789
                                ],
                                4551
                            )
                        ]
                    )
                ),
                2023,
                'sign_key_secret'
            ),
            $this->readFileXmlPaymentRequest('completionWithTransaction.xml'),
        ];

        yield 'Reversal request' => [
            (new ReversalFactory($this->createSaltGenerator()))->create(
                new ReversalDTO(
                    12345678,
                    ['reversal_id' => '123456']
                ),
                2023,
                'sign_key_secret'
            ),
            $this->readFileXmlPaymentRequest('reversal.xml')
        ];

        yield 'Refund request' => [
            (new RefundFactory($this->createSaltGenerator()))->create(
                new RefundDTO(
                    12345678,
                    null,
                    ['refund_id' => '123456']
                ),
                2023,
                'sign_key_secret'
            ),
            $this->readFileXmlPaymentRequest('refund.xml')
        ];

        yield 'Status request' => [
            (new StatusFactory($this->createSaltGenerator()))->create(
                new StatusDTO(12345678),
                2023,
                'sign_key_secret'
            ),
            $this->readFileXmlPaymentRequest('status.xml')
        ];
    }
}
