<?php
require_once 'helpers.php';
require_once 'conndb.php';

// Функция является "точкой входа" во всю валидацию.
function validate(array $inputArray, array $validationRules, $dbConnection): array
{
    // Объявим массив ошибок, который в итоге вернем в ответ.
    $errors = [];

//    Пройдемся по всему списку валидаций. Он выглядит примерно так
//    [
//        'Поле' => ['проверка1', 'проверка 2']
//    ]
    foreach ($validationRules as $field => $rules) {

        // Так как у нас условия - массив
        foreach($rules as $rule) {

            // У нас возможно передавать дополнительные параметры в функцию
            // Для этого используется формат названиеПроверки:<параметр>,<параметр2>,<парамер3>
            // Вытащим из правила название и параметры.
            $ruleParameters = explode(':', $rule);
            $ruleName = $ruleParameters[0];
            $ruleName = 'validate' . ucfirst($ruleName);
            $parameters = [];
            if (isset($ruleParameters[1])) {
                $parameters = explode(',', $ruleParameters[1]);
            }

            // Здесь мы проверим, что функция для проверки существует. Если нет - разработчик ошибся.
            // Предупредим его здесь
            if (!function_exists($ruleName)) {
                throw new Exception("Валидации {$ruleName} не существует. Пожалуйста, не забудьте добавить ее");
            }

            // Вызовем эту функцию со стандартными параметрами и параметрами, которые разработчик передал дополнительно.
            $errors[$field] = call_user_func_array($ruleName, array_merge([$inputArray, $field, $dbConnection], $parameters));
        }
    }

    // Так как у нас появится валидация для всех полей - отфильтруем ее только для тех полей, где она не прошла.
    return array_filter($errors);
}

// Простейшая функция
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

function validateEmail(array $inputArray, string $field, $dbConnection): ?string
{
    if (!isset($inputArray[$field])) {
        return null;
    }

    return filter_var($inputArray[$field], FILTER_VALIDATE_EMAIL) === false ? 'Введите корректный email' : null;
}
