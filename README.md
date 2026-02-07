# PHPUnit Mocks Rules

[![Downloads](https://img.shields.io/packagist/dt/rector/mockstan.svg?style=flat-square)](https://packagist.org/packages/rector/mockstan/stats)

Set of x+ PHPStan fun and practical rules that check:

@todo

Useful for any type of PHP project, from legacy to modern stack.

<br>

## Install

```bash
composer require rector/mockstan --dev
```

*Note: Make sure you use [`phpstan/extension-installer`](https://github.com/phpstan/extension-installer#usage) to load necessary service configs.*

<br>

@todo rules docs here

### ParamNameToTypeConventionRule

Interface must be located in "Contract" or "Contracts" namespace

```yaml
rules:
    - Rector\Mockstan\Rules\CheckRequiredInterfaceInContractNamespaceRule
```

```php
namespace App\Repository;

interface ProductRepositoryInterface
{
}
```

:x:

<br>

```php
namespace App\Contract\Repository;

interface ProductRepositoryInterface
{
}
```

:+1:

<br>

Happy coding!
