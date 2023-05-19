<h1>Делит файл по размерам<h1>
<?php
// Путь к исходному файлу CSV
$input_file = 'input.csv';

// Открываем исходный файл для чтения
$input_handle = fopen($input_file, 'r');

// Счетчик для подсчета строк
$row_counter = 0;

// Счетчик для создания новых файлов
$file_counter = 30;

// Читаем исходный файл построчно
while (($line = fgets($input_handle)) !== false) {
    // Пропускаем пустые строки
    if (trim($line) == '') {
        continue;
    }
    // Если достигнуто максимальное количество строк,
    // закрываем текущий файл и открываем новый
    if ($row_counter % 34 == 0) {
        if (isset($output_handle)) {
            fclose($output_handle);
        }
        $output_file = $file_counter . '.csv';
        $output_handle = fopen($output_file, 'w');
        $file_counter++;
    }
    // Записываем текущую строку в текущий файл
    if (trim($line) != '') {
        fwrite($output_handle, $line);
    }
    // Увеличиваем счетчик строк
    $row_counter++;
}

fclose($output_handle);

// Удаление пустых строк из новых файлов
for ($i = 30; $i < $file_counter; $i++) {
    $file_name = $i . '.csv';
    $lines = file($file_name, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    file_put_contents($file_name, implode("\n", $lines));
}
echo "Разделение файлов по размерам завершено.";