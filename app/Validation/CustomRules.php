<?php

namespace App\Validation;

class CustomRules
{
    public function required_if($str, string $fields, array $data): bool
    {
        [$otherField, $otherValue] = explode(',', $fields);

        // If the other field exists and matches the value
        if (isset($data[$otherField]) && $data[$otherField] === trim($otherValue)) {
            // If str is null or empty, return false (required)
            return !empty($str ?? '');
        }

        return true;
    }
}
