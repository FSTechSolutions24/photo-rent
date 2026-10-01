<?php

namespace Tests\Unit;

use App\Support\PhoneNumber;
use PHPUnit\Framework\TestCase;

class PhoneNumberTest extends TestCase
{
    /** @dataProvider egyptianNumberProvider */
    public function test_it_normalizes_egyptian_mobile_numbers(string $input, string $expected): void
    {
        $this->assertSame($expected, PhoneNumber::egyptian($input));
    }

    public function egyptianNumberProvider(): array
    {
        return [
            ['01012345678', '+201012345678'],
            ['+20 10 1234 5678', '+201012345678'],
            ['00201012345678', '+201012345678'],
            ['1012345678', '+201012345678'],
        ];
    }
}
