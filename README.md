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

```markdown
### ExplicitExpectsMockMethodRule

Require explicit `expects()` usage when setting up mocks to avoid silent stubs.

```yaml
rules:
    - Rector\Mockstan\Rules\ExplicitExpectsMockMethodRule
```

```php
// Bad (implicit stubbing)
$mock = $this->createMock(Service::class);
$mock->method('calculate')->willReturn(10);
```

:x:

```php
// Good (explicit expects)
$mock = $this->createMock(Service::class);
$mock->expects($this->any())->method('calculate')->willReturn(10);
```

<br>

### ForbiddenClassToMockRule

Disallow mocking of forbidden/core classes (e.g. `\DateTime`, framework internals).

```yaml
rules:
    - Rector\Mockstan\Rules\ForbiddenClassToMockRule
```

```php
// Bad
$dtMock = $this->createMock(\DateTime::class);
```

:x:

```php
// Good
$dt = new \DateTime();
```

<br>

### NoDocumentMockingRule

Prevent mocking of document classes (persisted models) — use real instances or factories.

```yaml
rules:
    - Rector\Mockstan\Rules\NoDocumentMockingRule
```

```php
// Bad
$docMock = $this->createMock(App\Document\User::class);
```

:x:

```php
// Good
$user = new App\Document\User();
```

<br>

### NoDoubleConsecutiveTestMockRule

Avoid creating multiple consecutive mocks in test body that indicate poor test design.

```yaml
rules:
    - Rector\Mockstan\Rules\NoDoubleConsecutiveTestMockRule
```

```php
// Bad
$a = $this->createMock(A::class);
$b = $this->createMock(B::class);
```

:x:

```php
// Good — combine setup or use single test-specific fixture
$a = $this->createMock(A::class);
// configure $a as needed, or refactor test
```

<br>

### NoEntityMockingRule

Do not mock entity classes (Doctrine entities); use real entity instances.

```yaml
rules:
    - Rector\Mockstan\Rules\NoEntityMockingRule
```

```php
// Bad
$entityMock = $this->createMock(App\Entity\Product::class);
```

:x:

```php
// Good
$product = new App\Entity\Product();
```

<br>

### NoMockObjectAndRealObjectPropertyRule

Disallow assigning a mock to a property while another test uses the real object on the same property.

```yaml
rules:
    - Rector\Mockstan\Rules\NoMockObjectAndRealObjectPropertyRule
```

```php
$this->service = $this->createMock(Service::class);
$this->service = new Service();
```

:x:

```php
// Good — keep property consistent or isolate tests
$this->service = $this->createMock(Service::class);

// or
$this->service = new Service();
```

:+1:


<br>

### NoMockOnlyTestRule

Detect tests that only create mocks and never assert behavior — require meaningful assertions.

```yaml
rules:
    - Rector\Mockstan\Rules\NoMockOnlyTestRule
```

```php
// Bad
public function testSomething()
{
    $this->createMock(Dependency::class);
}
```

:x:

```php
public function testSomething()
{
    $dep = $this->createMock(Dependency::class);
    $this->assertInstanceOf(Dependency::class, $dep);
}
```

:+1:




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
