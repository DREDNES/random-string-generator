<?php
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
    $res = random($string);
    $host = "127.0.0.1:3306";
    $user = "root";
    $password = "12345678";

    $db = new PDO("mysql:host={$host}; dbname=random_strings", $user, $password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    $stmt = $db->prepare('INSERT IGNORE INTO strings (hash, str) VALUES (:hash, :str)');
    return $stmt->execute([
        ':hash' => hash('md5', $res),
        ':str' => $res,
    ]);
}

$string = "{Пожалуйста,|Просто|Если сможете,} сделайте так, чтобы это
{удивительное|крутое|простое|важное|бесполезное} тестовое предложение {изменялось
{быстро|мгновенно|оперативно|правильно} случайным образом|менялось каждый раз}.";

if (set_to_db($string)) {
    echo 'Success!';
}
