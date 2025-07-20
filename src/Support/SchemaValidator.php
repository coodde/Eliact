<?php


namespace Coodde\Eliact\Support;

use Coodde\Eliact\Constants\DataTypes;

class SchemaValidator
{
    public function validate(array $data, array $schema): array
    {
        $result = [];
        foreach ($schema as $key => $type) {
            $value = $data[$key] ?? null;

            if (isset($this->customTypes[$type])) {
                $result[$key] = call_user_func($this->customTypes[$type], $value);
                continue;
            }

            switch ($type) {
                case DataTypes::STRING:
                    $result[$key] = is_string($value) ? $value : null;
                    break;
                case DataTypes::EMAIL:
                    $result[$key] = filter_var($value, FILTER_VALIDATE_EMAIL) ? $value : null;
                    break;
                case DataTypes::DATE:
                    $timestamp = strtotime($value);
                    $result[$key] = $timestamp ? date('Y-m-d', $timestamp) : null;
                    break;
                case DataTypes::INT:
                    $result[$key] = filter_var($value, FILTER_VALIDATE_INT) !== false ? (int)$value : null;
                    break;
                case DataTypes::FLOAT:
                    $result[$key] = filter_var($value, FILTER_VALIDATE_FLOAT) !== false ? (float)$value : null;
                    break;
                case DataTypes::BOOL:
                    if (is_bool($value)) {
                        $result[$key] = $value;
                    } elseif (is_string($value)) {
                        $result[$key] = in_array(strtolower($value), ['true', 'yes', '1']) ? true : false;
                    } else {
                        $result[$key] = (bool)$value;
                    }
                    break;
                case DataTypes::PHONE:
                    $result[$key] = preg_match('/^\+?[0-9\s\-\(\)]+$/', (string)$value) ? $value : null;
                    break;
                case DataTypes::URL:
                    $result[$key] = filter_var($value, FILTER_VALIDATE_URL) ? $value : null;
                    break;
                case DataTypes::DATETIME:
                    $timestamp = strtotime($value);
                    $result[$key] = $timestamp ? date('c', $timestamp) : null;
                    break;
                case DataTypes::UUID:
                    $result[$key] = preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[1-5][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i', $value) ? $value : null;
                    break;
                case DataTypes::CREDITCARD:
                    $result[$key] = preg_match('/^\\d{13,19}$/', preg_replace('/\\D/', '', $value)) ? $value : null;
                    break;
                case DataTypes::ZIP:
                    $result[$key] = preg_match('/^\\d{4,10}$/', $value) ? $value : null;
                    break;
                case DataTypes::COUNTRY:
                    $result[$key] = preg_match('/^[A-Z]{2,3}$/', strtoupper($value)) ? strtoupper($value) : null;
                    break;
                case DataTypes::SSTRING:
                    $sanitized = strip_tags($value);
                    $result[$key] = htmlspecialchars($sanitized, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
                    break;
                case DataTypes::CHOICE:
                    if (isset($schema["__choices__"][$key]) && is_array($schema["__choices__"][$key])) {
                        $allowed = $schema["__choices__"][$key];
                        $result[$key] = in_array($value, $allowed, true) ? $value : null;
                    } else {
                        $result[$key] = $value;
                    }
                    break;
                default:
                    $result[$key] = $value;
            }
        }

        return $result;
    }
}
