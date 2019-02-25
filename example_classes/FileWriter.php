<?php

require_once 'FileAbstract.php';

// FileWriter is a concrete class that can be instantiated.
// It must implement all methods declared abstract in FileAbstract.
class FileWriter extends FileAbstract {
    public function __construct(string $file_name)
    {
        // we need to make sure to call the parent's constructor
        parent::__construct($file_name);
    }

    public function getFileMode(): string
    {
        return 'wb';
    }

    public function getLockType(): int
    {
        return LOCK_EX;
    }

    // since this class will be used to write to a file, provide a writeString method to write a line to the file
    public function writeString(string $string_to_write): void {
        // if the file isn't open, we can't write a line
        if ($this->file_handle === null) {
            throw new Exception("Cannot write to file " . $this->getFileName() . " because it is not currently open.");
        }

        // use the fwrite function to write to the file handle
        $write_result = fwrite($this->file_handle, $string_to_write);
        // if the result is false there was an error, so throw an exception
        if ($write_result === false) {
            throw new Exception("Failed to write to file " . $this->getFileName() . "!");
        }
    }
}