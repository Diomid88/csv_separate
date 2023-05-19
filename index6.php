Делит файл на 4 строки
<?php
// Указываем имя входного каталога
$inputDir = "sep4";

// Получаем список файлов в каталоге
$files = scandir($inputDir);

// Создаем новую папку с именем входного каталога
$outputDir = $inputDir . "_output";
if (!file_exists($outputDir)) {
    mkdir($outputDir, 0777, true);
}

// Обрабатываем каждый файл входного каталога
foreach ($files as $file) {
    // Пропускаем "." и ".."
    if ($file == "." || $file == "..") {
        continue;
    }
    // Открываем файл для чтения
    $handle = fopen($inputDir . "/" . $file, "r");

    // Создаем новую папку с именем файла
    $fileDir = $outputDir . "/" . pathinfo($file, PATHINFO_FILENAME);
    if (!file_exists($fileDir)) {
        mkdir($fileDir, 0777, true);
    }

    // Читаем и записываем строки по 4 штуки, пока не достигнем конца файла
    $targetFileNumber = 1;
    $lineCount = 0;
    $lines = array();
    while (!feof($handle)) {
        // Читаем строку
        $line = fgets($handle);
        // Удаляем пустые строки
        $line = trim($line);
        // Если строка не пустая, добавляем ее в массив
        if (strlen($line) > 0) {
            $lines[] = $line;
            $lineCount++;
        }
        // Если массив достиг трех строк, создаем новый целевой файл и записываем в него три строки
        if ($lineCount == 4) {
            $targetFile = "target" . $targetFileNumber . ".csv";
            $targetHandle = fopen($fileDir . "/" . $targetFile, "w");
            fwrite($targetHandle, implode("\n", $lines));
            fclose($targetHandle);
            $targetFileNumber++;
            $lines = array();
            $lineCount = 0;
        }
    }
    // Если в массиве осталась одна или две строки, создаем новый целевой файл и записываем их в него
    if ($lineCount > 0) {
        $targetFile = "target" . $targetFileNumber . ".csv";
        $targetHandle = fopen($fileDir . "/" . $targetFile, "w");
        fwrite($targetHandle, implode("\n", $lines));
        fclose($targetHandle);
    }
    // Закрываем файл
    fclose($handle);
}
