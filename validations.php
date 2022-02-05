<?php
require_once 'helpers.php';
require_once 'conndb.php';

function validate(array $inputArray, array $validationRules, $dbConnection): array
{
    $errors = [];
    foreach ($validationRules as $field => $rules) {
        foreach($rules as $rule) {
            $ruleParameters = explode(':', $rule);
            $ruleName = $ruleParameters[0];
            $ruleName = 'validate' . ucfirst($ruleName);
            $parameters = [];
            if (isset($ruleParameters[1])) {
                $parameters = explode(',', $ruleParameters[1]);
            }

            if (!function_exists($ruleName)) {
                throw new Exception("Валидации {$ruleName} не существует. Пожалуйста, не забудьте добавить ее");
            }

            $errors[$field] = call_user_func_array($ruleName, array_merge([$inputArray, $field, $dbConnection], $parameters));
        }
    }

    return array_filter($errors);
}

function validateRequired(array $inputArray, string $field, $dbConnection): ?string
{
    if (!isset($inputArray[$field])) {
        return 'Поле должно быть заполнено';
    }

    return null;
}

function validateString(array $inputArray, string $field, $dbConnection): ?string
{
    if (!isset($inputArray[$field])) {
        return null;
    }

    return is_string($inputArray[$field]) ? null : 'Поле должно быть строкой';
}

function validateMin(array $inputArray, string $field, $dbConnection, $min): ?string
{
    if (!isset($inputArray[$field])) {
        return null;
    }

    switch (gettype($inputArray[$field])) {
        case 'string':
            $symbol = get_noun_plural_form($min, 'символ', 'символа', 'символов');
            return strlen($inputArray[$field]) > $min ? null : "Минимальная длина: {$min} {$symbol}";
        case 'integer':
        case 'double':
            return $inputArray[$field] > $min ? null : "Минимальное значение: {$min}";
        case 'array':
            return count($inputArray[$field]) > $min ? null : "Минимальное количество элементов: {$min}";
        default:
            return null;
    }
}

function validateDate(array $inputArray, string $field, $dbConnection): ?string
{
    if (!isset($inputArray[$field])) {
        return null;
    }

    return (bool)strtotime($inputArray[$field]) ? null : 'Введенное значение должно быть датой';
}

function validateAfter(array $inputArray, string $field, $dbConnection, $date): ?string
{
    if (!isset($inputArray[$field])) {
        return null;
    }

    return strtotime($date) <= strtotime($inputArray[$field]) ? null : "Дата должна быть позже {$date}";
}

function validateExists(array $inputArray, string $field, $dbConnection, $table, $dbField): ?string
{
    if (!isset($inputArray[$field])) {
        return null;
    }

    // Здесь можно использовать формирование строки из введенных параметров, так как
    // эти параметры задаются в коде и не могут быть введены пользователем
    $query = "select * from {$table} where {$dbField} = ? limit 1";
    $stmt = db_get_prepare_stmt($dbConnection, $query, [$inputArray[$field]]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);

    return count($rows) > 0 ? null : 'Выбранное значение должно существовать в базе данных';

}
