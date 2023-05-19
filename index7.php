<h1>Делит файлы с размерами на магазины</h1><br>
<?php

// Создаем основную папку "size_output" (если она еще не существует)
$outputDirectory = 'size_output';
if (!is_dir($outputDirectory)) {
    mkdir($outputDirectory);
}

// Получаем список файлов в папке "size"
$sourceDirectory = 'size';
$sourceFiles = glob($sourceDirectory . '/*.csv');

foreach ($sourceFiles as $sourceFile) {
    // Получаем имя исходного файла без расширения
    $sourceFileName = basename($sourceFile, '.csv');

    // Создаем папку с именем исходного файла (если она еще не существует)
    $outputSubDirectory = $outputDirectory . '/' . $sourceFileName;
    if (!is_dir($outputSubDirectory)) {
        mkdir($outputSubDirectory);
    }

    $fileHandle = fopen($sourceFile, 'r');

    if ($fileHandle) {
        // Заданные количество строк для каждого файла
        $fileRowCounts = [
            '1' => 3,
            '3' => 3,
            '5' => 3,
            '6' => 3,
            '18' => 3,
            '33' => 3,
            '2' => 2,
            '4' => 2,
            '7' => 2,
            '11' => 2,
            '12' => 2,
            '19' => 2,
            '31' => 2,
            '8' => 2,

        ];

        foreach ($fileRowCounts as $fileName => $rowCount) {
            $outputFile = $outputSubDirectory . '/' . $fileName . '.csv';
            $outputHandle = fopen($outputFile, 'w');

            if ($outputHandle) {
                // Читаем строки из исходного файла и записываем указанное количество строк в новый файл
                $linesWritten = 0;
                $currentRow = 0;
                $line = '';
                $linesToWrite = [];
                while (($currentRow < $rowCount) && (($line = fgets($fileHandle)) !== false)) {
                    $trimmedLine = trim($line); // Удаляем лишние пробелы и символы перевода строки
                    if (!empty($trimmedLine)) { // Пропускаем пустые строки
                        $linesToWrite[] = $line;
                        $linesWritten++;
                        $currentRow++;
                    }
                }

                // Удаляем последнюю пустую строку, если она есть
                $lastLineIndex = count($linesToWrite) - 1;
                if ($lastLineIndex >= 0 && empty(trim($linesToWrite[$lastLineIndex]))) {
                    unset($linesToWrite[$lastLineIndex]);
                }

                fwrite($outputHandle, implode('', $linesToWrite)); // Записываем строки в новый файл

                // Удаляем последний символ перевода строки, чтобы избежать создания пустой строки
                
                ftruncate($outputHandle, ftell($outputHandle));

                fclose($outputHandle);

                echo "Файл <b>$outputFile</b> успешно создан.<br>";
            } else {
                echo "Ошибка при создании файла $outputFile.\n";
            }

            if ($linesWritten < $rowCount) {
                break;
            }
        }

        fclose($fileHandle);
    } else {
        echo "Ошибка при открытии файла $sourceFile.\n";
    }
}

?>
