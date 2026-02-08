<?php

declare(strict_types=1);

namespace Rector\Mockstan\Enum;

/**
 * @api
 */
final class SymfonyClass
{
    public const string REQUEST = 'Symfony\Component\HttpFoundation\Request';

    public const string VALIDATOR_TEST_CASE = 'Symfony\Component\Validator\Test\ConstraintValidatorTestCase';

    public const string SYMFONY_EVENT = 'Symfony\Contracts\EventDispatcher\Event';

    public const string REQUEST_STACK = 'Symfony\Component\HttpFoundation\RequestStack';

    public const string FORM_TYPE_TEST_CASE = 'Symfony\Component\Form\Test\TypeTestCase';
}
