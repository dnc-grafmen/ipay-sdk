<?php

declare(strict_types=1);

namespace Integration;

use GuzzleHttp\Psr7\Response;
use IPaySdk\Entity\CompletionEntityResponse;
use IPaySdk\Entity\CreatePaymentEntityResponse;
use IPaySdk\Entity\EntityInterface;
use IPaySdk\Entity\TransactionEntityResponse;
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
        $class = get_class($expectedEntity);
        $entity = $this->responseTransformService->convertResponse($response, $class);

        dump($entity);

        self::assertTrue($entity instanceof $class);
        self::assertEquals($expectedEntity, $entity);
    }

    public function convertResponseDataProvider(): iterable
    {
        return [
            [
                $this->createResponse(
                    '<?xml version="1.0" encoding="utf-8" standalone="yes"?>
<payment>
    <pid>12345678</pid>
    <status>1</status>
    <salt>e9be5bc9a02a5af61efecd722b7b05e84d106d1a</salt>
    <sign>0c8698119b846202bd87d948cfce1eba7bc535c49dca4752fe8f969abefd05147b4b87fd9332173e242a0f1a78a42eed8c1846d4781a220fd564f0fbce3ff393</sign>
    <url>https://checkout.ipay.ua/a1f7e6a6ced6fc72d4dbb48da6babc7d2ca89ac2</url>
</payment>'
                ),
                new CreatePaymentEntityResponse(
                    12345678,
                    1,
                    'e9be5bc9a02a5af61efecd722b7b05e84d106d1a',
                    '0c8698119b846202bd87d948cfce1eba7bc535c49dca4752fe8f969abefd05147b4b87fd9332173e242a0f1a78a42eed8c1846d4781a220fd564f0fbce3ff393',
                    'https://checkout.ipay.ua/a1f7e6a6ced6fc72d4dbb48da6babc7d2ca89ac2'
                ),
            ],
            [
                $this->createResponse(
                    '<?xml version="1.0" encoding="utf-8" standalone="yes"?>
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
</payment>'
                ),
                new CompletionEntityResponse(
                    12345678,
                    5,
                    'a98688b38115a7b42f55ca74a2fe6f6fb536b57d',
                    '566d16af0f9aeb12d055a2117160194ec0868ce418152a6ffb82a84037b4f28b9d345ee7f84a0374101771f891779af013e26e3540c26f4734d8717097f39bc9',
                    '2019-07-02 11:08:55',
                    [
                        new TransactionEntityResponse(
                            11223344,
                            26501014380602,
                            300346,
                            37973023,
                            'ПАТ "АЛЬФА-БАНК"',
                            100,
                            110,
                        ),
                    ]
                ),
            ],
        ];
    }

    private function createResponse(string $body, int $status = 200, array $headers = []): ResponseInterface
    {
        return new Response($status, $headers, $body);
    }
}
