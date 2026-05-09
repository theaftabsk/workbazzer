<?php
/**
 * Enterprise Validation System
 * Handles secure and robust input validation.
 */

class Validator {
    private array $data;
    private array $errors = [];

    public function __construct(array $data) {
        $this->data = $data;
    }

    public static function make(array $data): self {
        return new self($data);
    }

    public function validate(array $rules): bool {
        foreach ($rules as $field => $ruleString) {
            $ruleSet = explode('|', $ruleString);
            $value = $this->data[$field] ?? null;

            foreach ($ruleSet as $rule) {
                $this->applyRule($field, $value, $rule);
            }
        }

        return empty($this->errors);
    }

    private function applyRule(string $field, $value, string $rule) {
        if (strpos($rule, 'min:') === 0) {
            $min = (int) substr($rule, 4);
            if (strlen((string) $value) < $min) {
                $this->addError($field, "The $field must be at least $min characters.");
            }
        } elseif (strpos($rule, 'max:') === 0) {
            $max = (int) substr($rule, 4);
            if (strlen((string) $value) > $max) {
                $this->addError($field, "The $field may not be greater than $max characters.");
            }
        } else {
            switch ($rule) {
                case 'required':
                    if ($value === null || trim((string) $value) === '') {
                        $this->addError($field, "The $field field is required.");
                    }
                    break;
                case 'email':
                    if ($value && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                        $this->addError($field, "The $field must be a valid email address.");
                    }
                    break;
                case 'phone':
                    if ($value && !preg_match('/^\d{10}$/', Security::phone($value))) {
                        $this->addError($field, "The $field must be a valid 10-digit phone number.");
                    }
                    break;
                case 'numeric':
                    if ($value !== null && !is_numeric($value)) {
                        $this->addError($field, "The $field must be a number.");
                    }
                    break;
            }
        }
    }

    private function addError(string $field, string $message) {
        if (!isset($this->errors[$field])) {
            $this->errors[$field] = [];
        }
        $this->errors[$field][] = $message;
    }

    public function fails(): bool {
        return !empty($this->errors);
    }

    public function errors(): array {
        return $this->errors;
    }

    public function firstError(): ?string {
        if (empty($this->errors)) return null;
        $firstField = reset($this->errors);
        return $firstField[0] ?? null;
    }
}
