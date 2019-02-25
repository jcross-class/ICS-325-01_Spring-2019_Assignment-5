<?php

require_once 'FileWriter.php';

// FileAppender is very similar to FileWriter.
// The only difference is the file mode used to open the file.
class FileAppender extends FileWriter
{
    public function __construct(string $file_name)
    {
        parent::__construct($file_name);
    }

    public function getFileMode(): string
    {
        return 'ab';
    }
}