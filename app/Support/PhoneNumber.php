<?php

namespace App\Support;

class PhoneNumber
{
    /** Normalize a supported Egyptian mobile number to E.164 format. */
    public static function egyptian(?string $value): string
    {
        $digits = preg_replace('/\D+/', '', (string) $value);

        if (str_starts_with($digits, '00')) {
            $digits = substr($digits, 2);
        }

        if (preg_match('/^01[0125]\d{8}$/', $digits)) {
            return '+20'.substr($digits, 1);
        }

        if (preg_match('/^1[0125]\d{8}$/', $digits)) {
            return '+20'.$digits;
        }

        if (preg_match('/^201[0125]\d{8}$/', $digits)) {
            return '+'.$digits;
        }

        return (string) $value;
    }
}
