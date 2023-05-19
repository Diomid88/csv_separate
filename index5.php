<h1>Объединяет файлы в разных каталогах</h1><br>


<?php

// Установите пути к двум каталогам с файлами
$directory1 = 'conc/39/';
$directory2 = 'conc/39_2/';

// Установите путь к каталогу, где будут сохранены объединенные файлы
$outputDirectory = 'conc_output/';

// Получаем список файлов в первом каталоге
$files1 = scandir($directory1);

// Объединяем файлы из первого каталога
foreach ($files1 as $file1) {
    // Пропускаем точки и специальные файлы
    if ($file1 === '.' || $file1 === '..') {
        continue;
    }
    
    // Получаем путь к файлу из первого каталога
    $filePath1 = $directory1 . $file1;
    
    // Получаем путь к файлу из второго каталога с тем же именем
    $filePath2 = $directory2 . $file1;
    
    // Проверяем, существует ли файл второго каталога
    if (file_exists($filePath2)) {
        // Читаем содержимое файлов
        $content1 = file_get_contents($filePath1);
        $content2 = file_get_contents($filePath2);
        
        // Создаем новый файл для записи
        $outputFile = $outputDirectory . $file1;
        
        // Объединяем содержимое файлов
        $mergedContent = $content1 . "\n" . $content2;
        
        // Записываем объединенное содержимое в новый файл
        file_put_contents($outputFile, $mergedContent);
        
        echo "Объединение файлов \"$file1\" завершено. Результат сохранен в $outputFile.<br>";
    }
}

echo "Объединение файлов завершено.";

?>
