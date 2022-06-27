#PHP IPay SDK
######Simple PHP sdk for IPay api
###Installation
`composer require dnc-grafmen/ipay-sdk`
###Usage
Make the `IPayClientFactory` and call his method `create` with parameters for create the `IPayClient`
####Example
```PHP
$merchantId = 2300;
$signKey = 'my_super_secret_sign_key_from_account';
$factory = new \IPaySdk\Factory\IPayClientFactory();
$clientSandbox = $factory->create($merchantId, $signKey, new \GuzzleHttp\Client(), \IPaySdk\Constants::URL_SANDBOX);
// or for production
$clientProduction = $factory->create($merchantId, $signKey, new \GuzzleHttp\Client());
```
The next step - make the factory which implemented the `PaymentFactoryInterface`.
####Example
```PHP
$saltGenerator = new \IPaySdk\Generator\MicrotimeSaltGenerator();
$factory = new \IPaySdk\Factory\CreatePaymentFactory($saltGenerator);
```
And we also need to create a DTO that implements `DataDTOInterface`
####Example
```PHP
$dto = new \IPaySdk\DTO\CreatePaymentDTO(
    new \IPaySdk\DTO\UrlsDTO('https://my.site.com/payment/ok', 'https://my.site.com/payment/fail'),
    new \IPaySdk\DTO\TransactionDTOCollection([
        new \IPaySdk\DTO\TransactionDTO(
            5500, // in cents
            'UAH',
            'Description about the transaction',
            ['orderid' => 'uc25-fsf4-vfg4-2312-fv845-dcvn-24fs-12bk'],
        )
    ]),
    24,
    'ua',
    ['ua' => 'ua message', 'ru' => 'ru message', 'en' => 'en message']
);
```

And put this data to client send method.
####Example
```PHP
$response = $client->send($factory, $dto);
```
You got the `ApiResponseInterface` object

The factory knows what type of answer you will get

For more information check the [api documentation](https://checkout.ipay.ua/doc)

###Full code
```PHP
$merchantId = 2300;
$signKey = 'my_super_secret_sign_key_from_account';
$factory = new \IPaySdk\Factory\IPayClientFactory();
$client = $factory->create($merchantId, $signKey, new \GuzzleHttp\Client(), \IPaySdk\Constants::URL_SANDBOX);

$saltGenerator = new \IPaySdk\Generator\MicrotimeSaltGenerator();
$factory = new \IPaySdk\Factory\CreatePaymentFactory($saltGenerator);

$dto = new \IPaySdk\DTO\CreatePaymentDTO(
    new \IPaySdk\DTO\UrlsDTO('https://my.site.com/payment/ok', 'https://my.site.com/payment/fail'),
    new \IPaySdk\DTO\TransactionDTOCollection([
        new \IPaySdk\DTO\TransactionDTO(
            5500, // in cents
            'UAH',
            'Description about the transaction',
            ['orderid' => 'uc25-fsf4-vfg4-2312-fv845-dcvn-24fs-12bk'],
        )
    ]),
    24,
    'ua',
    ['ua' => 'ua message', 'ru' => 'ru message', 'en' => 'en message']
);

$response = $client->send($factory, $dto);
```
