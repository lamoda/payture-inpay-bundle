# Payture InPay Client Bundle

Wrapper bundle for [lamoda/payture-inpay-client](https://github.com/lamoda/payture-inpay-client)

## Installation

```bash
composer require lamoda/payture-inpay-bundle
```

```php
<?php
// Kernel
/// ...
$bundles[] = new \Lamoda\Payture\InPayBundle\PaytureInPayBundle();
///
```

```yaml
# services.yaml
payture_inpay:
  terminals:
    TestTerminal:
      auth:
        key: MerchantKey
        password: MerchantPassword
        url: https://sandbox.payture.com
```

## Usage

```php
<?php
$container->get(\Lamoda\Payture\InPayBundle\Terminal\TerminalRegistry::class)->get('TestTerminal')->charge('ORDER_NUMBER_123', 100500);
```

## Tuning

### Custom guzzle client

You can configure guzzle client used for every terminal using `payture_inpay.guzzle_client` option key, 
passing your own guzzle client service ID there. New client would be instantiated for you if none provided, i.e

```yaml
  default_options:
    operations:
      Init:
        timeout: 2
        connect_timeout: 0.5
```

### Client configuration

Currently you can configure timeouts for each operation using both `payture_inpay.default_options.operations` 
for global setting or `payture_inpay.terminals.<TerminalName>.operations` for terminal level setting.

### Logging

You can configure `payture_inpay.logging` with logger service ID to be passed to all inner services of bundle.

Also you can configure generic Guzzle [logging middleware](http://docs.guzzlephp.org/en/stable/handlers-and-middleware.html). 
