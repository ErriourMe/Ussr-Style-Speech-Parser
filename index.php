<?php

require_once('ParseStringClass.php');
require_once('DBClass.php');

$start = microtime(true);


$string = 'Товарищи!< С другой стороны::< Равным:: Таким:: Должным > образом> практика показывает, что <реализация <намеченных заданий::развития <<организационной::форм> деятельности::обучения кадров:: плановых заданий>>::постоянный рост активности> требует от нас анализа <новых предложений::<финансовых::административных> условий:: поставленных <задач::целей>>';
echo $string."<br>";

$parser = new ParseString($string);
try {
    $result = $parser->getStrings();
} catch (Exception $e) {
    echo $e->getMessage();
    die;
}

$inserts = mb_substr(str_repeat('(?), ', count($result)), 0, -2);
DB::query("INSERT INTO `strings` (`value`) VALUES {$inserts} ON DUPLICATE KEY UPDATE `value` = VALUES(`value`)", $result);

echo '<br><br>Done';
echo '<br>===============================================================<br>';
echo 'Время выполнения скрипта: ' . round((microtime(true) - $start)*1000, 3) . ' ms';