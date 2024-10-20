<?php

namespace Pfilsx\PostgreSQLDoctrine\Tests\Fixtures\Enum;

enum TestIntBackedEnum: int
{
    case Case1 = 1;
    case Case2 = 2;
    case Case3 = 3;
}
