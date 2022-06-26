<?php

declare(strict_types=1);

namespace IPaySdk\Tests\Integration;

use GuzzleHttp\ClientInterface;
use IPaySdk\Constants;
use IPaySdk\DTO\CompletionDTO;
use IPaySdk\DTO\CompletionWithTransactionDTO;
use IPaySdk\DTO\CreatePaymentDTO;
use IPaySdk\DTO\DataDTOInterface;
use IPaySdk\DTO\RefundDTO;
use IPaySdk\DTO\ReversalDTO;
use IPaySdk\DTO\StatusDTO;
use IPaySdk\DTO\TransactionDTO;
use IPaySdk\DTO\TransactionDTOCollection;
use IPaySdk\DTO\UrlsDTO;
use IPaySdk\Factory\CompletionFactory;
use IPaySdk\Factory\CompletionWithTransactionFactory;
use IPaySdk\Factory\CreatePaymentFactory;
use IPaySdk\Factory\IPayClientFactory;
use IPaySdk\Factory\PaymentFactoryInterface;
use IPaySdk\Factory\RefundFactory;
use IPaySdk\Factory\ReversalFactory;
use IPaySdk\Factory\StatusFactory;
use IPaySdk\Generator\MicrotimeSaltGenerator;
use IPaySdk\Response\ApiResponseInterface;
use IPaySdk\Response\CompletionResponse;
use IPaySdk\Response\CreatePaymentResponse;
use IPaySdk\Response\RefundResponse;
use IPaySdk\Response\ReversalResponse;
use IPaySdk\Response\StatusResponse;
use IPaySdk\Response\TransactionResponse;
use IPaySdk\Response\TransactionsCollection;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class IPayClientTest extends TestCase
{
    use TestUtils;

    /**
     * @dataProvider sendDataProvider
     *
     * @param string                  $filenameApiResponse
     * @param PaymentFactoryInterface $paymentFactory
     * @param DataDTOInterface        $data
     * @param ApiResponseInterface    $expectedResponse
     */
    public function testSend(
        string $filenameApiResponse,
        PaymentFactoryInterface $paymentFactory,
        DataDTOInterface $data,
        ApiResponseInterface $expectedResponse
    ): void {
        $client = (new IPayClientFactory())->create(
            2023,
            'my_secret_key',
            $this->createMockGuzzleClient($filenameApiResponse),
            Constants::URL_SANDBOX
        );
        $response = $client->send($paymentFactory, $data);

        self::assertEquals($expectedResponse, $response);
    }

    private function createMockGuzzleClient(string $filenameApiResponse): ClientInterface
    {
        $data = trim($this->readFileXmlPaymentResponse($filenameApiResponse));

        $streamMock = $this->createMock(StreamInterface::class);
        $streamMock->method('getContents')->willReturn($data);

        $responseMock = $this->createMock(ResponseInterface::class);
        $responseMock->method('getBody')->willReturn($streamMock);
        $responseMock->method('getStatusCode')->willReturn(200);

        $clientMock = $this->createMock(ClientInterface::class);
        $clientMock->method('request')->willReturn($responseMock);

        return $clientMock;
    }

    public function sendDataProvider(): iterable
    {
        $saltGenerator = new MicrotimeSaltGenerator();

        $transactionDTO1 = new TransactionDTO(55, 'UAH', 'Покупка товара/услуги', '{"dogovor":123456}', 4301);
        $transactionDTO2 = new TransactionDTO(200, 'UAH', 'Покупка услуги/товара', '{"dogovor":56789}', 4551);

        $transactionsCollection = (new TransactionsCollection())->add(
            (new TransactionResponse())
                ->setTrnId(11223344)
                ->setSmchRr(26501014380602)
                ->setSmchMfo(300346)
                ->setSmchOkpo(37973023)
                ->setSmchBank('ПАТ "АЛЬФА-БАНК"')
                ->setInvoice(100)
                ->setAmount(110)
        );

        yield 'CreatePayment test' => [
            'createPayment.xml',
            new CreatePaymentFactory($saltGenerator),
            new CreatePaymentDTO(
                new UrlsDTO('http://www.example.com/ok/', 'http://www.example.com/fail/'),
                new TransactionDTOCollection([
                    $transactionDTO1
                ]),
                24,
                'ru',
                '{"ru":"название на русском","ua":"назва на українській","en":"english name"}',
            ),
            (new CreatePaymentResponse())
                ->setPaymentId(12345678)
                ->setStatus(1)
                ->setSalt('e9be5bc9a02a5af61efecd722b7b05e84d106d1a')
                ->setSign('0c8698119b846202bd87d948cfce1eba7bc535c49dca4752fe8f969abefd05147b4b87fd9332173e242a0f1a78a42eed8c1846d4781a220fd564f0fbce3ff393')
                ->setUrl('https://checkout.ipay.ua/a1f7e6a6ced6fc72d4dbb48da6babc7d2ca89ac2')
        ];

        yield 'Completion test' => [
            'completion.xml',
            new CompletionFactory($saltGenerator),
            new CompletionDTO(12345678),
            (new CompletionResponse())
                ->setPaymentId(12345678)
                ->setStatus(5)
                ->setSalt('a98688b38115a7b42f55ca74a2fe6f6fb536b57d')
                ->setSign('566d16af0f9aeb12d055a2117160194ec0868ce418152a6ffb82a84037b4f28b9d345ee7f84a0374101771f891779af013e26e3540c26f4734d8717097f39bc9')
                ->setSaleDate('2019-07-02 11:08:55')
                ->setTransactions($transactionsCollection)
        ];

        yield 'Completion with transaction test' => [
            'completion.xml',
            new CompletionWithTransactionFactory($saltGenerator),
            new CompletionWithTransactionDTO(
                12345678,
                new TransactionDTOCollection([
                    $transactionDTO1,
                    $transactionDTO2,
                ])
            ),
            (new CompletionResponse())
                ->setPaymentId(12345678)
                ->setStatus(5)
                ->setSalt('a98688b38115a7b42f55ca74a2fe6f6fb536b57d')
                ->setSign('566d16af0f9aeb12d055a2117160194ec0868ce418152a6ffb82a84037b4f28b9d345ee7f84a0374101771f891779af013e26e3540c26f4734d8717097f39bc9')
                ->setSaleDate('2019-07-02 11:08:55')
                ->setTransactions($transactionsCollection)
        ];

        yield 'Reversal test' => [
            'reversal.xml',
            new ReversalFactory($saltGenerator),
            new ReversalDTO(
                12345678,
                '{"reversal_id":"123456"}',
            ),
            (new ReversalResponse())
                ->setPaymentId(12345678)
                ->setStatus(9)
                ->setSalt('a98688b38115a7b42f55ca74a2fe6f6fb536b57d')
                ->setSign('566d16af0f9aeb12d055a2117160194ec0868ce418152a6ffb82a84037b4f28b9d345ee7f84a0374101771f891779af013e26e3540c26f4734d8717097f39bc9')
                ->setSaleDate('2019-07-02 11:08:55')
                ->setTransactions($transactionsCollection)
        ];

        yield 'Refund test' => [
            'refund.xml',
            new RefundFactory($saltGenerator),
            new RefundDTO(
                12345678,
                null,
                '{"refund_id":"123456"}',
            ),
            (new RefundResponse())
                ->setPaymentId(12345678)
                ->setStatus(9)
                ->setSalt('a98688b38115a7b42f55ca74a2fe6f6fb536b57d')
                ->setSign('566d16af0f9aeb12d055a2117160194ec0868ce418152a6ffb82a84037b4f28b9d345ee7f84a0374101771f891779af013e26e3540c26f4734d8717097f39bc9')
                ->setSaleDate('2019-07-02 11:08:55')
                ->setTransactions($transactionsCollection)
        ];

        yield 'Status test' => [
            'status.xml',
            new StatusFactory($saltGenerator),
            new StatusDTO(12345678),
            (new StatusResponse())
                ->setPaymentId(12345678)
                ->setStatus(1)
                ->setSalt('cc348f94880ed17b1b09e1061ff6984d88042cc8')
                ->setSign('d11b748a790102fe36f6949eba2ad3aceb4e358c7cf54813384dfb71bef22b4037376ae6767af60ac9676280b89f27822acaaf376c1d27571f778c6859505502')
                ->setInitDate('2021-03-19 12:33:17')
                ->setAmount(30)
                ->setInvoice(30)
                ->setDescription('test')
                ->setCardMask('***')
        ];
    }
}
