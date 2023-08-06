<?php

function findBigFolders($dir, $prefix = "Биг") {
    $result = array();

    // Получаем список всех элементов в текущей директории
    $files = scandir($dir);

    foreach ($files as $file) {
        // Игнорируем специальные элементы "." и ".."
        if ($file == '.' || $file == '..') continue;

        $path = $dir . DIRECTORY_SEPARATOR . $file;

        // Если текущий элемент - директория
        if (is_dir($path)) {
            // Преобразуем имя файла в UTF-8 для корректного сравнения
            $filenameUtf8 = iconv(mb_detect_encoding($file, mb_detect_order(), true), 'UTF-8//IGNORE', $file);

            // Проверяем, соответствует ли имя директории заданному префиксу "Биг"
            if (mb_stripos($filenameUtf8, $prefix, 0, 'UTF-8') === 0) {
                $result[$file] = $path;
            }

            // Рекурсивно вызываем функцию для поддиректории
            $subResult = findBigFolders($path, $prefix);
            $result = array_merge($result, $subResult);
        }
    }

    return $result;
}

function mergeCSVFiles($dir) {
    $csvData = array();

    // Получаем список CSV файлов в папке
    $csvFiles = glob($dir . DIRECTORY_SEPARATOR . '*.csv');

    foreach ($csvFiles as $csvFile) {
        if (($handle = fopen($csvFile, "r")) !== false) {
            while (($data = fgetcsv($handle, 1000, ",")) !== false) {
                $csvData[] = $data;
            }
            fclose($handle);
        }
    }

    // Сохраняем объединенные данные в новый CSV файл с именем папки
    $outputFile = "test" . DIRECTORY_SEPARATOR . basename($dir) . ".csv";

    if (($handle = fopen($outputFile, "w")) !== false) {
        foreach ($csvData as $row) {
            fputcsv($handle, $row);
        }
        fclose($handle);
    }

    return $outputFile;
}

// Укажите начальную директорию, откуда начнется поиск (например, текущая директория)
$startingDir = "test";

// Укажите префикс, который должен содержаться в именах папок (например, "Биг")
$folderPrefix = "Биг";

// Вызываем функцию и получаем список папок с указанным префиксом
$bigFolders = findBigFolders($startingDir, $folderPrefix);

// Объединяем CSV файлы в каждой папке и сохраняем в папке "test"
foreach ($bigFolders as $folderName => $folderPath) {
    $mergedFile = mergeCSVFiles($folderPath);
    echo "Объединенный файл для папки '$folderName' создан: $mergedFile <br>";
}
?>
