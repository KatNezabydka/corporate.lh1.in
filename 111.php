<?php
//pclzip – библиотека для рбаоты с zip архивом (в php.ini нужно чето сделать)
//нужно подключить requre_once
//1)	создание архива с нуля
$archive = new \PclZip('archive.zip');
$result = $archive->create('file.txt');
If ($result == 0) {
    echo $archive->errorInfo(true);
}
//2)	Добавление файлов в архив
$archive2 = new \PclZip('archive.zip'); //указіваем уже существующий архив
$archive2->add('file2.txt');

//3)	Вывод содержимого архива
$archive = new \PclZip('archive.zip');
$result = $archive->listContent();
If ($result == 0) {
    echo $archive->errorInfo(true);
}
//$id - индекс файла в архиве, можно по нему удалить
foreach($result as $id=>$v1) {
//перебор массива с файлами
    foreach($v1 as $kk=>$v2){
        echo "$kk - $v2";
    }
}

//Удаление
$archive = new \PclZip('archive.zip');
$result = $archive->delete();
//удалить только часть
$archive->delete(PCLZIP_OPT_BY_INDEX,'1,2');

// разархиывирование
$archive = new \PclZip('archive.zip');
//folder1 - папка куда разархивировать
$result = $archive->extract(PCLZIP_OPT_PATH,'folder1');

//ВСТРОЕННАЯ БИБЛИОТЕКА ДЛЯ РАБОТЫ С АРХИВОМ \ZipArchive()
$zip = new \ZipArchive();
$zip_name = 'name.zip';
//$zip->addFile();
//указали браузеру, что это zip
header('Content-type: application/zip');
header('Content-Disposition: attachment; filename= ".$zip_name."');
readfile($zip_name);
unlink($zip_name);

//ВСТРОЕННАЯ БИБЛИОТЕКА ДЛЯ РАБОТЫ с рассылкой phpmailer

