# UniPayment PHP Client
[![GitHub license](https://img.shields.io/badge/license-MIT-blue.svg?style=flat-square)](https://github.com/UniCryptoLab/UniPaymentClient.PHP/blob/main/UniPaymentClient/LICENSE.txt)
[![Packagist](https://img.shields.io/packagist/v/unipayment/client.svg?style=flat-square)](https://packagist.org/packages/unipayment/client)

A PHP client for the [UniPayment Client API](https://unipayment.readme.io/reference/overview).  

This SDK provides a convenient abstraction of UniPayment's Gateway API and allows developers to focus on payment flow/e-commerce integration rather than on the specific details of client-server interaction using the raw API.


## Getting Started

[SDK Tutorial](https://unipayment.io/en/docs/sdk/tutorial/)

Before using the UniPayment API, sign up for your [API key](https://console.unipayment.io/).

If you want to use the Sandbox, sign up [here](https://sandbox-console.unipayment.io/).

## Installation

### Install Composer
```bash
curl -sS https://getcomposer.org/installer | php
```

### Install via composer
Add unipayment/client into require section of composer.json 
```json
{
  "require": {
    "unipayment/client": "1.*"
  }
}

```

## Initializing UniPayment client
```php
client = new \UniPayment\Client\UniPaymentClient();
client->getConfig()->setClientId("your client id");
client->getConfig()->setClientSecret("you client secret");

```

Sandbox is used in the same way with is_sandbox as true.

```php
$client = new \UniPayment\Client\UniPaymentClient();
$client->getConfig()->setClientId("your client id");
$client->getConfig()->setClientSecret("you client secret");
$client->getConfig()->setIsSandbox(true)
```

## Create an invoice
> Reference：https://unipayment.readme.io/reference/create_invoice

```php
$client_id='your client id'
$client_secret='your client secret'
$app_id = 'your payment app id'

$createInvoiceRequest = new \UniPayment\Client\Model\CreateInvoiceRequest();
$createInvoiceRequest->setAppId($app_id);
$createInvoiceRequest->setPriceAmount("10.05");
$createInvoiceRequest->setPriceCurrency("USD");
$createInvoiceRequest->setNotifyUrl("https://example.com/notify");
$createInvoiceRequest->setRedirectUrl("https://example.com/redirect");
$createInvoiceRequest->setOrderId("#123456");
$createInvoiceRequest->setTitle("MacBook Air");
$createInvoiceRequest->setDescription("MacBookAir (256#)");


$client = new \UniPayment\Client\UniPaymentClient();
$client->getConfig()->setClientId($client_id);
$client->getConfig()->setClientSecret($client_secret);

create_invoice_response = $this->uniPaymentClient->createInvoice($createInvoiceRequest);
```
### CreateInvoiceResponse

```json
{
  "code": "OK",
  "msg": "",
  "data": {
    "app_id": "cee1b9e2-d90c-4b63-9824-d621edb38012",
    "invoice_id": "Dj2mNCXXWCGKT89kcU8NJn",
    "order_id": "ORDER_123456",
    "price_amount": 2.0,
    "price_currency": "USD",
    "network": null,
    "address": null,
    "pay_amount": 0.0,
    "pay_currency": null,
    "exchange_rate": 0.0,
    "paid_amount": 0.0,
    "create_time": "2022-09-14T06:31:57",
    "expiration_time": "2022-09-14T06:36:57",
    "confirm_speed": 2,
    "status": 1,
    "error_status": 0,
    "invoice_url": "https://sandbox-app.unipayment.io/i/Dj2mNCXXWCGKT89kcU8NJn"
  }
}

```

## Handle IPN
> Reference：https://unipayment.readme.io/reference/ipn-check

> Invoice Status: https://unipayment.readme.io/reference/invoice-status

IPNs (Instant Payment Notifications) are sent to the notify_url when order status is changed to paid, confirmed and complete.

```php

$app_id='your client id'
$api_key='your client secret'
$notify='{"ipn_type":"invoice","event":"invoice_created","app_id":"cee1b9e2-d90c-4b63-9824-d621edb38012","invoice_id":"12wQquUmeCPUx3qmp3aHnd","order_id":"ORDER_123456","price_amount":2.0,"price_currency":"USD","network":null,"address":null,"pay_currency":null,"pay_amount":0.0,"exchange_rate":0.0,"paid_amount":0.0,"confirmed_amount":0.0,"refunded_price_amount":0.0,"create_time":"2022-09-14T04:57:54.5599307Z","expiration_time":"2022-09-14T05:02:54.559933Z","status":"New","error_status":"None","ext_args":"Merchant Pass Through Data","transactions":null,"notify_id":"fd58cedd-67c6-4053-ae65-2f6fb09a7d2c","notify_time":"0001-01-01T00:00:00"}';

$client = new \UniPayment\Client\UniPaymentClient();
$client->getConfig()->setClientId($app_id);
$client->getConfig()->setClientSecret($api_key);

$response = $$client->checkIpn($notify);

```

IPN notify
``` json
{
	"ipn_type": "invoice",
	"event": "invoice_expired",
	"app_id": "cee1b9e2-d90c-4b63-9824-d621edb38012",
	"invoice_id": "3Q7fyLnB2YNhUDW1fFNyEz",
	"order_id": "20",
	"price_amount": 6.0,
	"price_currency": "SGD",
	"network": null,
	"address": null,
	"pay_currency": null,
	"pay_amount": 0.0,
	"exchange_rate": 0.0,
	"paid_amount": 0.0,
	"confirmed_amount": 0.0,
	"refunded_price_amount": 0.0,
	"create_time": "2022-09-12T03:36:03",
	"expiration_time": "2022-09-12T03:41:03",
	"status": "Expired",
	"error_status": "None",
	"ext_args": null,
	"transactions": null,
	"notify_id": "8ccd2b61-226b-48e5-99b8-acb1f350313e",
	"notify_time": "2022-09-12T03:56:10.5852752Z"
}
```

## Run Example

1.Get source code form GitHub 
``` bash
git clone https://github.com/UniCryptoLab/UniPaymentClient.PHP.git
```

2.Run project in PHPStorm


## License

MIT License

Copyright (c) 2021 UniPayment

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.