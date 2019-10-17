<?php

function createArrayOfUniqueStrings($string)
{
    $comb = [];
    while (count($comb) < 75) { //75 - это число возможных комбинаций строки, посчитал вручную, потому что не смог автоматизировать :(
        $generated_string = random($string);
        $hash = $hash = hash('md5', $generated_string);
        if (!array_key_exists($hash, $comb)) {
            $comb[$hash] = $generated_string;
        }
    }
    return $comb;
}

function random($string)
{
    while (strpos($string, "{") !== false) {
        $string = preg_replace_callback(
            '/{([^{}]+)}/',
            function ($subStr) {
                $strPart = $subStr[1];
                if (strpos($strPart, "|") === false) {
                    return $strPart;
                }
                $explodedStr = explode("|", $strPart);
                return $explodedStr[array_rand($explodedStr)];
            }, $string);
    }
    return $string;
}

function set_to_db($string)
{
    $host = "127.0.0.1:3306";
    $user = "root";
    $password = "12345678";

    $db = new PDO("mysql:host={$host}; dbname=random_strings", $user, $password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

    $generatedStrings = createArrayOfUniqueStrings($string);
    $result = true;
    foreach ($generatedStrings as $hash => $value) {
        $stmt = $db->prepare('INSERT IGNORE INTO strings (hash, str) VALUES (:hash, :str)');
        if (!$stmt->execute([':hash' => $hash, ':str' => $value])) {
            $result = false;
        }
    }
    return $result;
}

$string = "{Пожалуйста,|Просто|Если сможете,} сделайте так, чтобы это
{удивительное|крутое|простое|важное|бесполезное} тестовое предложение {изменялось
{быстро|мгновенно|оперативно|правильно} случайным образом|менялось каждый раз}.";

if (set_to_db($string)) {
    echo "Success!";
} else {
    echo "Error.";
}
