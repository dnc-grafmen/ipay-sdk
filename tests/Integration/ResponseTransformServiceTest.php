<?php

declare(strict_types=1);

namespace IPaySdk\Tests\Integration;

use GuzzleHttp\Psr7\Response;
use IPaySdk\Response\CompletionResponse;
use IPaySdk\Response\ApiResponseInterface;
use IPaySdk\Response\CreatePaymentResponse;
use IPaySdk\Response\RefundResponse;
use IPaySdk\Response\ReversalResponse;
use IPaySdk\Response\StatusResponse;
use IPaySdk\Response\TransactionResponse;
use IPaySdk\Response\TransactionsCollection;
use IPaySdk\Service\ResponseTransformService;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

class ResponseTransformServiceTest extends TestCase
{
    use TestUtils;

    private ResponseTransformService $responseTransformService;

    protected function setUp(): void
    {
        $this->responseTransformService = new ResponseTransformService();
    }

    /**
     * @dataProvider convertResponseDataProvider
     *
     * @param ResponseInterface $response
     * @param ApiResponseInterface $expectedEntity
     */
    public function testConvertResponse(ResponseInterface $response, ApiResponseInterface $expectedEntity): void
    {
        $class = get_class($expectedEntity);
        $entity = $this->responseTransformService->convertResponse($response, $class);

        self::assertTrue($entity instanceof $class);
        self::assertEquals($expectedEntity, $entity);
    }

    public function convertResponseDataProvider(): iterable
    {
        yield 'Create payment response' => [
            $this->createResponse($this->readFileXmlPaymentResponse('createPayment.xml')),
            (new CreatePaymentResponse())
                ->setPaymentId(12345678)
                ->setStatus(1)
                ->setSalt('e9be5bc9a02a5af61efecd722b7b05e84d106d1a')
                ->setSign('0c8698119b846202bd87d948cfce1eba7bc535c49dca4752fe8f969abefd05147b4b87fd9332173e242a0f1a78a42eed8c1846d4781a220fd564f0fbce3ff393')
                ->setUrl('https://checkout.ipay.ua/a1f7e6a6ced6fc72d4dbb48da6babc7d2ca89ac2'),
        ];

        yield 'Completion response' => [
            $this->createResponse($this->readFileXmlPaymentResponse('completion.xml')),
            (new CompletionResponse())
                ->setPaymentId(12345678)
                ->setStatus(5)
                ->setSalt('a98688b38115a7b42f55ca74a2fe6f6fb536b57d')
                ->setSign('566d16af0f9aeb12d055a2117160194ec0868ce418152a6ffb82a84037b4f28b9d345ee7f84a0374101771f891779af013e26e3540c26f4734d8717097f39bc9')
                ->setSaleDate('2019-07-02 11:08:55')
                ->setTransactions((new TransactionsCollection())->add(
                    (new TransactionResponse())
                        ->setTrnId(11223344)
                        ->setSmchRr(26501014380602)
                        ->setSmchMfo(300346)
                        ->setSmchOkpo(37973023)
                        ->setSmchBank('ПАТ "АЛЬФА-БАНК"')
                        ->setInvoice(100)
                        ->setAmount(110)
                )),
        ];

        yield 'Completion with transaction response' => [
            $this->createResponse($this->readFileXmlPaymentResponse('completionWithTransaction.xml')),
            (new CompletionResponse())
                ->setPaymentId(12345678)
                ->setStatus(5)
                ->setSalt('a97688b38115a7b42f45ca75a2fe6f6fb536b57d')
                ->setSign('566d16af0f9aeb32d055a2117160194ec0868ce418152a6ffb83a84037b4f28b9d345ee7f84a0374101771f891779ad013e26e3540c26f4734d8717097f39bc9')
                ->setSaleDate('2019-07-02 11:08:55')
                ->setTransactions((new TransactionsCollection())
                    ->add(
                        (new TransactionResponse())
                            ->setTrnId(11223344)
                            ->setSmchRr(26501014380602)
                            ->setSmchMfo(300346)
                            ->setSmchOkpo(37973023)
                            ->setSmchBank('ПАТ "АЛЬФА-БАНК"')
                            ->setInvoice(100)
                            ->setAmount(110)
                    )
                    ->add(
                        (new TransactionResponse())
                            ->setTrnId(55667788)
                            ->setSmchRr(26501014380602)
                            ->setSmchMfo(300346)
                            ->setSmchOkpo(37973023)
                            ->setSmchBank('ПАТ "АЛЬФА-БАНК"')
                            ->setInvoice(200)
                            ->setAmount(220)
                    )
                ),
        ];

        yield 'Reversal response' => [
            $this->createResponse($this->readFileXmlPaymentResponse('reversal.xml')),
            (new ReversalResponse())
                ->setPaymentId(12345678)
                ->setStatus(9)
                ->setSalt('a98688b38115a7b42f55ca74a2fe6f6fb536b57d')
                ->setSign('566d16af0f9aeb12d055a2117160194ec0868ce418152a6ffb82a84037b4f28b9d345ee7f84a0374101771f891779af013e26e3540c26f4734d8717097f39bc9')
                ->setSaleDate('2019-07-02 11:08:55')
                ->setTransactions((new TransactionsCollection())->add(
                    (new TransactionResponse())
                        ->setTrnId(11223344)
                        ->setSmchRr(26501014380602)
                        ->setSmchMfo(300346)
                        ->setSmchOkpo(37973023)
                        ->setSmchBank('ПАТ "АЛЬФА-БАНК"')
                        ->setInvoice(100)
                        ->setAmount(110)
                )),
        ];

        yield 'Refund response' => [
            $this->createResponse($this->readFileXmlPaymentResponse('refund.xml')),
            (new RefundResponse())
                ->setPaymentId(12345678)
                ->setStatus(9)
                ->setSalt('a98688b38115a7b42f55ca74a2fe6f6fb536b57d')
                ->setSign('566d16af0f9aeb12d055a2117160194ec0868ce418152a6ffb82a84037b4f28b9d345ee7f84a0374101771f891779af013e26e3540c26f4734d8717097f39bc9')
                ->setSaleDate('2019-07-02 11:08:55')
                ->setTransactions((new TransactionsCollection())->add(
                    (new TransactionResponse())
                        ->setTrnId(11223344)
                        ->setSmchRr(26501014380602)
                        ->setSmchMfo(300346)
                        ->setSmchOkpo(37973023)
                        ->setSmchBank('ПАТ "АЛЬФА-БАНК"')
                        ->setInvoice(100)
                        ->setAmount(110)
                )),
        ];

        yield 'Status response' => [
            $this->createResponse($this->readFileXmlPaymentResponse('status.xml')),
            (new StatusResponse())
                ->setPaymentId(12345678)
                ->setStatus(5)
                ->setSalt('cc348f94880ed17b1b09e1061ff6984d88042cc8')
                ->setSign('d11b748a790102fe36f6949eba2ad3aceb4e358c7cf54813384dfb71bef22b4037376ae6767af60ac9676280b89f27822acaaf376c1d27571f778c6859505502')
                ->setCardMask('***')
                ->setInvoice(30)
                ->setAmount(30)
                ->setDescription('test')
                ->setInitDate('2021-03-19 12:33:17')
                ->setBankErrorGroup(0)
                ->setBankErrorNote('')
        ];
    }

    private function createResponse(string $body, int $status = 200, array $headers = []): ResponseInterface
    {
        return new Response($status, $headers, $body);
    }
}
