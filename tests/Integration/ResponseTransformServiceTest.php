<?php

declare(strict_types=1);

namespace Integration;

use GuzzleHttp\Psr7\Response;
use IPaySdk\Response\CompletionResponse;
use IPaySdk\Response\EntityInterface;
use IPaySdk\Response\TransactionResponse;
use IPaySdk\Response\TransactionsCollection;
use IPaySdk\Service\ResponseTransformService;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

class ResponseTransformServiceTest extends TestCase
{
    private ResponseTransformService $responseTransformService;

    protected function setUp(): void
    {
        $this->responseTransformService = new ResponseTransformService();
    }

    /**
     * @dataProvider convertResponseDataProvider
     *
     * @param ResponseInterface $response
     * @param EntityInterface   $expectedEntity
     */
    public function testConvertResponse(ResponseInterface $response, EntityInterface $expectedEntity): void
    {
        $class  = get_class($expectedEntity);
        $entity = $this->responseTransformService->convertResponse($response, $class);


        self::assertTrue($entity instanceof $class);
        self::assertEquals($expectedEntity, $entity);
    }

    public function convertResponseDataProvider(): iterable
    {
        return [
            [
                $this->createResponse(
                    <<<XML
<?xml version="1.0" encoding="utf-8" standalone="yes"?>
<payment>
    <pid>12345678</pid>
    <status>5</status>
    <sale_date>2019-07-02 11:08:55</sale_date>
    <transactions>
        <transaction>
            <trn_id>11223344</trn_id>
            <smch_rr>26501014380602</smch_rr>
            <smch_mfo>300346</smch_mfo>
            <smch_okpo>37973023</smch_okpo>
            <smch_bank>ПАТ "АЛЬФА-БАНК"</smch_bank>
            <invoice>100</invoice>
            <amount>110</amount>
        </transaction>
    </transactions>
    <salt>a98688b38115a7b42f55ca74a2fe6f6fb536b57d</salt>
    <sign>566d16af0f9aeb12d055a2117160194ec0868ce418152a6ffb82a84037b4f28b9d345ee7f84a0374101771f891779af013e26e3540c26f4734d8717097f39bc9</sign>
</payment>
XML
                ),
                (new CompletionResponse())
                    ->setPid(12345678)
                    ->setStatus(5)
                    ->setSalt('a98688b38115a7b42f55ca74a2fe6f6fb536b57d')
                    ->setSign(
                        '566d16af0f9aeb12d055a2117160194ec0868ce418152a6ffb82a84037b4f28b9d345ee7f84a0374101771f891779af013e26e3540c26f4734d8717097f39bc9'
                    )
                    ->setSaleDate('2019-07-02 11:08:55')
                    ->setTransactions(
                        (new TransactionsCollection())->add(
                            (new TransactionResponse())
                                ->setTrnId(11223344)
                                ->setSmchRr(26501014380602)
                                ->setSmchMfo(300346)
                                ->setSmchOkpo(37973023)
                                ->setSmchBank('ПАТ "АЛЬФА-БАНК"')
                                ->setInvoice(100)
                                ->setAmount(110)
                        )
                    ),
            ],
        ];
    }

    private function createTransactionEntityResponse(): TransactionResponse
    {
        return (new TransactionResponse())
            ->setTrnId(11223344)
            ->setSmchRr(26501014380602)
            ->setSmchMfo(300346)
            ->setSmchOkpo(37973023)
            ->setSmchBank('ПАТ "АЛЬФА-БАНК"')
            ->setInvoice(100)
            ->setAmount(110);
    }

    private function createResponse(string $body, int $status = 200, array $headers = []): ResponseInterface
    {
        return new Response($status, $headers, $body);
    }
}
