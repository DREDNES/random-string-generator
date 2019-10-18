<?php

function createArrayOfUniqueStrings($string)
{
    $comb = [];
    $combinationsCount = getCombinationsCount($string);
    while (count($comb) < $combinationsCount) {
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

function getCombinationsCount($string)
{

    while (strpos($string, "{") !== false) {
        preg_match_all('/{([^{}]+)}/', $string, $matches);
        $parts[] = $matches[1];
        $string = preg_replace_callback('/{([^{}]+)}/',
            function ($subStr) {
                $strPart = $subStr[0];
                return "pastHere:{$subStr[1]}";
            }, $string);
    }

    $flatten = new RecursiveIteratorIterator(new RecursiveArrayIterator($parts));
    $flatten = iterator_to_array($flatten, false);
    foreach ($flatten as $index => $part) {
        $pos = strpos($part, "pastHere:");
        if ($pos !== false) {
            $rest = substr($part, $pos + 9, 15);
            foreach ($flatten as $cmpIndex => $cmpPart) {
                if ($index != $cmpIndex) {
                    if ($rest == substr($cmpPart, 0, 15)) {
                        unset($flatten[$cmpIndex]);
                    }
                }
            }
        }
    }
    $arrayCounts = array_map(function ($part) {
        return count(explode("|", $part));
    }, $flatten);

    return array_product($arrayCounts);
}

function setToDb($data)
{
    $host = "127.0.0.1:3306";
    $user = "root";
    $password = "12345678";

    $db = new PDO("mysql:host={$host}; dbname=random_strings", $user, $password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

    $result = true;
    foreach ($data as $hash => $value) {
        $stmt = $db->prepare('INSERT IGNORE INTO strings (hash, str) VALUES (:hash, :str)');
        if (!$stmt->execute([':hash' => $hash, ':str' => $value])) {
            $result = false;
        }
    }
    return $result;
}

$string = "{Пожалуйста,|Просто|Если сможете,} сделайте так, чтобы это {удивительное|крутое|простое|важное|бесполезное} тестовое предложение {изменялось {быстро|мгновенно|оперативно|правильно} случайным образом|менялось каждый раз}.";
$generatedStrings = createArrayOfUniqueStrings($string);

if (setToDb($generatedStrings)) {
    echo "Success!";
} else {
    echo "Error.";
}
