<?php

require_once 'FileWriter.php';
require_once 'FileAppender.php';
require_once 'FileReader.php';

// we'll use FileReader to read from the file read_test_file.txt
$my_file = new FileReader("read_test_file.txt");
$my_file->open();
// using $my_file with echo causes PHP to call the __toString() method automatically
echo $my_file . "\n";
while ($my_file->file_end_of_file() === false) {
    echo $my_file->getLine() . "\n";
}
$my_file->close();

// we'll use FileWriter to write to the file write_test_file.txt
$my_file = new FileWriter("write_test_file.txt");
$my_file->open();
echo $my_file . "\n";
$my_file->writeString("Testing here!\n");
$my_file->writeString("More tests!\n");
$my_file->close();

// we'll use FileAppender to append to the file write_test_file.txt
$my_file = new FileAppender("write_test_file.txt");
$my_file->open();
echo $my_file . "\n";
$my_file->writeString("Appending once!\n");
$my_file->writeString("Appending twice!\n");
$my_file->close();