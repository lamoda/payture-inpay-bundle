# Payture InPay Client Bundle

Wrapper bundle for [lamoda/payture-inpay-php-client](https://github.com/lamoda/payture-inpay-php-client)

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

## Configuration reference

```yaml
payture_inpay:
    terminals:

        # Prototype
        name:
            name:                 ~

            # Terminal authentication data
            auth:                 # Required

                # Terminal operation URL
                url:                  ~ # Required, Example: https://sandbox.payture.com/

                # Terminal identification Key
                key:                  ~ # Required, Example: Merchant

                # Terminal identification Password
                password:             ~ # Required, Example: Secret
            operations:
                Init:

                    # Operation timeout, seconds
                    timeout:              null

                    # Connection timeout for operation, seconds
                    connect_timeout:      null
                Charge:

                    # Operation timeout, seconds
                    timeout:              null

                    # Connection timeout for operation, seconds
                    connect_timeout:      null
                Unblock:

                    # Operation timeout, seconds
                    timeout:              null

                    # Connection timeout for operation, seconds
                    connect_timeout:      null
                Refund:

                    # Operation timeout, seconds
                    timeout:              null

                    # Connection timeout for operation, seconds
                    connect_timeout:      null
                PayStatus:

                    # Operation timeout, seconds
                    timeout:              null

                    # Connection timeout for operation, seconds
                    connect_timeout:      null
    default_options:
        operations:
            Init:

                # Operation timeout, seconds
                timeout:              30 # Required

                # Connection timeout for operation, seconds
                connect_timeout:      5 # Required
            Charge:

                # Operation timeout, seconds
                timeout:              30 # Required

                # Connection timeout for operation, seconds
                connect_timeout:      5 # Required
            Unblock:

                # Operation timeout, seconds
                timeout:              30 # Required

                # Connection timeout for operation, seconds
                connect_timeout:      5 # Required
            Refund:

                # Operation timeout, seconds
                timeout:              30 # Required

                # Connection timeout for operation, seconds
                connect_timeout:      5 # Required
            PayStatus:

                # Operation timeout, seconds
                timeout:              30 # Required

                # Connection timeout for operation, seconds
                connect_timeout:      5 # Required

    # Guzzle client service ID. New one will be created if none provided
    guzzle_client:        null

    # Logger service ID. No logger by default
    logger:               null
```
