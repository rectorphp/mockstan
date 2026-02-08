# PHPUnit Mocks Rules

[![Downloads](https://img.shields.io/packagist/dt/rector/mockstan.svg?style=flat-square)](https://packagist.org/packages/rector/mockstan/stats)

* Do you use extensive mocking in your PHPUnit tests?
* Do you want to keep your tests clean, maintainable and avoid upgrade hell in the future?
* Do you want to have test that actually test somthing?

This set is for you!

<br>

## Install

```bash
composer require rector/mockstan --dev
```

*Note: Make sure you use [`phpstan/extension-installer`](https://github.com/phpstan/extension-installer#usage) to load necessary service configs.*

<br>

### AvoidAnyExpectsRule

Disallow usage of `any()` expectation in mocks to ensure that all mock interactions are explicitly defined and verified.

```yaml
rules:
    - Rector\Mockstan\Rules\AvoidAnyExpectsRule
```

```php
$someMock = $this->createMock(Service::class);
$someMock->expects($this->any())
    ->method('calculate')
    ->willReturn(10);
```

:x:

```php
$someMock = $this->createMock(Service::class);
$someMock->expects($this->once())
    ->method('calculate')
    ->willReturn(10);
```

:+1:


<br>

### ExplicitExpectsMockMethodRule

Require explicit `expects()` usage when setting up mocks to avoid silent stubs. This is reuired since PHPUnit 12 to avoid silent stubs.

```yaml
rules:
    - Rector\Mockstan\Rules\ExplicitExpectsMockMethodRule
```

```php
$someMock = $this->createMock(Service::class);
$someMock->method('calculate')->willReturn(10);
```

:x:

```php
$someMock = $this->createMock(Service::class);
$someMock->expects($this->once())->method('calculate')->willReturn(10);
```

:+1:

<br>

### ForbiddenClassToMockRule

Disallow mocking of forbidden/core classes (e.g. `\DateTime`, Symfony `Request` or `RequestStack`, `Iterable`, Symfony and Doctine event objects etc.).

```yaml
rules:
    - Rector\Mockstan\Rules\ForbiddenClassToMockRule
```

```php
$dateTimeMock = $this->createMock(\DateTime::class);
```

:x:

```php
$dateTime = new \DateTime();
```

:+1:

<br>

### NoDocumentMockingRule

Prevent mocking of Doctrine ODM document classes. Use real instances instead.

```yaml
rules:
    - Rector\Mockstan\Rules\NoDocumentMockingRule
```

```php
$docMock = $this->createMock(App\Document\User::class);
```

:x:

```php
$user = new App\Document\User();
```

:+1:

<br>

### NoDoubleConsecutiveTestMockRule

Avoid creating multiple consecutive methods mocks, one for params and other for return. Use single instead.

```yaml
rules:
    - Rector\Mockstan\Rules\NoDoubleConsecutiveTestMockRule
```

```php
$someMock = $this->createMock(SomeClass::class);

$someMock->expects($this->once())
    ->method('foo')
    ->willReturnOnConsecutiveCalls(...)
    ->willReturnCallback(...)
```

:x:

```php
$someMock = $this->createMock(SomeClass::class);

$someMock->expects($this->once())
    ->method('foo')
    // handle params in single call
    ->willReturnCallback(...)
```

:+1:

<br>

### NoEntityMockingRule

Do not mock Doctrine entity classes. Use real object instance instead.

```yaml
rules:
    - Rector\Mockstan\Rules\NoEntityMockingRule
```

```php
$entityMock = $this->createMock(App\Entity\Product::class);
```

:x:

```php
$product = new App\Entity\Product();
```

:+1:

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
$this->someMock = $this->createMock(AnotherService::class);

$this->realService = new Service();
```

:+1:


<br>

### NoMockOnlyTestRule

Avoid tests that only create mocks and never assert behavior. Require meaningful assertions with at least once real object to test.

```yaml
rules:
    - Rector\Mockstan\Rules\NoMockOnlyTestRule
```

```php
public function testNothing()
{
    $someMock = $this->createMock(Dependency::class);

    $someMock->expects($this->once())
        ->method('doSomething')
        ->willReturn(true);

    $this->assertSame($someMock->doSomething());
}
```

:x:

```php
public function testSomething()
{
    $someMock = $this->createMock(Dependency::class);
    $realObject = new RealObject($someMock);

    $this->assertTrue($realObject->doSomething());
}
```

:+1:

<br>

Happy coding!
