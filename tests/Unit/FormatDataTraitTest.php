<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Traits\FormatDataTrait;

class FormatDataTraitTest extends TestCase
{
    use FormatDataTrait; // Menggunakan trait yang akan diuji

    /**
     * @test
     * @dataProvider provideNumberParsingData
     */
    public function it_can_parse_various_number_formats_correctly($input, $expected)
    {
        $result = $this->parseNumber($input);
        
        // Menggunakan assertEquals dengan toleransi delta untuk perbandingan float
        $this->assertEquals($expected, $result, "Parsing failed for input: '{$input}'");
    }

    public function provideNumberParsingData()
    {
        return [
            // Format Excel/Sheets Umum
            'Standard Integer' => ['12345', 12345.0],
            'With Rp' => ['Rp 1.234.567', 1234567.0],
            'With Parentheses (Negative)' => ['(1.234)', -1234.0],
            'With Comma Decimal (ID Format)' => ['1.234,56', 1234.56],
            'With Percentage' => ['66,67%', 66.67],
            'Zero Value' => ['0', 0.0],
            'Empty String' => ['', 0.0],
            'Null Value' => [null, 0.0],
            'Negative with Dot' => ['-1000.50', -1000.50],
            'ID Format with Dot and Comma' => ['Rp 1.000.000,00', 1000000.0],
            'Only Comma' => ['100,5', 100.5],
            'Only Dot (Should be treated as thousands separator)' => ['1.000', 1000.0],
        ];
    }

    /**
     * @test
     */
    public function it_converts_numbers_to_roman_numerals()
    {
        $this->assertEquals('I', $this->toRoman(1));
        $this->assertEquals('IV', $this->toRoman(4));
        $this->assertEquals('I', $this->toRoman(99)); // Fallback
    }
}