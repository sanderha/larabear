<?php

namespace GuardsmanPanda\Larabear\Infrastructure\App\Service;


class BearShortUrlCodeService {
    private const CHARS = '256789bcdfghjklmnpqrstvwxzBCDFGHJKLMNPQRSTVWXZ';

    public static function generateNextCode(string $value): string {
        // Change value string into array of characters
        $value_array = str_split(string: $value);

        // Map each character to the index in the chars string
        $value_array = array_map(callback: static function($char) {
            return strpos(haystack: self::CHARS, needle: $char);
        }, array: $value_array);

        // Reverse the value array
        $value_array = array_reverse(array: $value_array);
        $value_array[0]++;

        // For each index, if it is greater than the length of the chars string, set it to 0 and increment the next element.
        foreach ($value_array as $i => $iValue) {
            if ($iValue >= strlen(string: self::CHARS)) {
                $value_array[$i] = 0;
                if (isset($value_array[$i + 1])) {
                    $value_array[$i + 1]++;
                } else {
                    $value_array[$i + 1] = 0;
                }
            }
        }

        // Map each index back to a character
        $value_array = array_map(callback: static function($index) {
            return self::CHARS[$index];
        }, array: $value_array);

        // join the array back into a string
        return implode(array: array_reverse(array: $value_array));
    }
}
