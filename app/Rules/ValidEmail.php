<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidEmail implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // 1. Check basic format
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $fail('The :attribute must be a valid email address.');
            return;
        }

        // 2. Extract domain from email
        $domain = substr(strrchr($value, "@"), 1);

        // 3. Check if domain has valid MX records (can receive email)
        if (!$this->hasMxRecords($domain)) {
            $fail('The email domain does not appear to accept emails. Please use a valid email address.');
            return;
        }

        // 4. For Gmail, do additional validation
        if (strtolower($domain) === 'gmail.com') {
            $localPart = substr($value, 0, strpos($value, '@'));
            
            // Gmail usernames must be 6-30 characters
            // Remove dots as Gmail ignores them
            $cleanLocalPart = str_replace('.', '', $localPart);
            
            if (strlen($cleanLocalPart) < 6 || strlen($cleanLocalPart) > 30) {
                $fail('Gmail addresses must have 6-30 characters before the @.');
                return;
            }

            // Gmail usernames can only contain letters, numbers, and dots
            if (!preg_match('/^[a-zA-Z0-9.]+$/', $localPart)) {
                $fail('Gmail addresses can only contain letters, numbers, and dots.');
                return;
            }

            // Cannot start or end with a dot
            if (str_starts_with($localPart, '.') || str_ends_with($localPart, '.')) {
                $fail('Gmail addresses cannot start or end with a dot.');
                return;
            }

            // Cannot have consecutive dots
            if (str_contains($localPart, '..')) {
                $fail('Gmail addresses cannot have consecutive dots.');
                return;
            }
        }
    }

    /**
     * Check if domain has MX records.
     */
    private function hasMxRecords(string $domain): bool
    {
        // Check MX records
        if (checkdnsrr($domain, 'MX')) {
            return true;
        }

        // Fallback: check A record (some domains use A record for mail)
        if (checkdnsrr($domain, 'A')) {
            return true;
        }

        return false;
    }
}
