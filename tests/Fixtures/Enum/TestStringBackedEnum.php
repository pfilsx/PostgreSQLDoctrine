<?php

namespace Pfilsx\PostgreSQLDoctrine\Tests\Fixtures\Enum;

enum TestStringBackedEnum: string
{
    case Case1 = 'Case1';
    case Case2 = 'Case2';
    case Case3 = 'Case3';
}
