Делит файл по 2 строки
<?php
// Указываем имя входного каталога
$inputDir = "sep2";

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

    // Читаем и записываем строки по 2 штуки, пока не достигнем конца файла
    $targetFileNumber = 1;
    while (!feof($handle)) {
        // Читаем первую строку
        $line1 = fgets($handle);
        // Если это не конец файла, читаем и записываем вторую строку
        if (!feof($handle)) {
            $line2 = fgets($handle);
            // Удаляем пустые строки из первой и второй строки
            $line1 = trim($line1);
            $line2 = trim($line2);
            // Если строки не пустые, создаем новый целевой файл и записываем в него две строки
            if (strlen($line1) > 0 && strlen($line2) > 0) {
                $targetFile = "target" . $targetFileNumber . ".csv";
                $targetHandle = fopen($fileDir . "/" . $targetFile, "w");
                fwrite($targetHandle, $line1 . "\n" . $line2);
                fclose($targetHandle);
                $targetFileNumber++;
            }
        }
        // Если это конец файла и осталась одна строка, записываем ее в отдельный файл, если она не пустая
        else {
            $line1 = trim($line1);
            if (strlen($line1) > 0) {
                $targetFile = "target" . $targetFileNumber . ".csv";
                $targetHandle = fopen($fileDir . "/" . $targetFile, "w");
                fwrite($targetHandle, $line1);
                fclose($targetHandle);
            }
        }
    }

    // Закрываем файл
    fclose($handle);
}
?>
