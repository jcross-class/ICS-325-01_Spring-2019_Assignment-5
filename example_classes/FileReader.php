<?php

require_once 'FileAbstract.php';

// FileReader is a concrete class that can be instantiated.
// It must implement all methods declared abstract in FileAbstract.
class FileReader extends FileAbstract {
    public function __construct(string $file_name)
    {
        // we need to make sure to call the parent's constructor
        parent::__construct($file_name);
    }

    public function getFileMode(): string
    {
        return 'rb';
    }

    public function getLockType(): int
    {
        return LOCK_SH;
    }

    // since this class will be used to read from a file, provide a getLine() method to fetch a line from the file
    public function getLine(): string
    {
        // if the file isn't open, we can't read a line
        if ($this->file_handle === null) {
            throw new Exception("Cannot read frrom file " . $this->getFileName() . " because it is not currently open.");
        }

        // if we're at the end of the file, return an empty string
        if($this->file_end_of_file()) {
            return '';
        }

        // use fgets() to read from the file handle and trim off any white space such as a new line at the end of the string
        return trim(fgets($this->file_handle));
    }
}
