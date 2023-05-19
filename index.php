Делит файл по 1 коду
<?php
$input_dir = 'input_csv_files';
$output_dir = 'output_csv_files';

if (!file_exists($output_dir)) {
    mkdir($output_dir, 0777, true);
}

$files = scandir($input_dir);

foreach ($files as $file) {
    if (in_array($file, array('.', '..'))) {
        continue;
    }

    $handle = fopen($input_dir . '/' . $file, 'r');

    $output_file_dir = $output_dir . '/' . basename($file, '.csv');
    if (!file_exists($output_file_dir)) {
        mkdir($output_file_dir, 0777, true);
    }

    $row_num = 1;
    while (($data = fgetcsv($handle)) !== false) {
        $output_file_path = $output_file_dir . '/BIG' . $row_num . '.csv';
        $output_file = fopen($output_file_path, 'w');

        // Записываем данные текущей строки в выходной файл
        fwrite($output_file, implode(',', $data));

        // Закрываем выходной файл
        fclose($output_file);

        $row_num++;
    }

    fclose($handle);
}
