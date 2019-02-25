<?php

// an abstract class acts as a template for children classes, it cannot be directly instantiated
abstract class FileAbstract
{
    // a private property or method can only be accessed by the class it was declared it, not its child classes
    private $file_name;
    // a protected property can be accessed by children classes, but not publicly
    protected $file_handle = null;

    // a public function or method can be accessed by any calling code
    // notice the type hint (type declaration) for $file_name is string
    public function __construct(string $file_name)
    {
        // assign the value passed into the constructor to an instance variable so we can refer to it in other methods
        $this->file_name = $file_name;
    }

    // a magic method that is called when the class is treated as a string
    function __toString()
    {
        $return_string = "File name: $this->file_name\n";
        // try to make the lock type more human readable
        if ($this->file_handle !== null) {
            switch($this->getLockType()) {
                case LOCK_SH:
                    $lock_type = "LOCK_SH";
                    break;
                case LOCK_EX:
                    $lock_type = "LOCK_EX";
                    break;
                default:
                    $lock_type = $this->getLockType();
            }
            $return_string .= "Lock type: " . $lock_type . "\n";
            $return_string .= "File mode: " . $this->getFileMode() . "\n";
        }

        return $return_string;
    }

    // notice the return type hint for this method is string
    public function getFileName(): string
    {
        return $this->file_name;
    }

    // this method doesn't return anything, so its return type hint is void
    public function open(): void
    {
        // if the file is already open, then $this->file_handle will not be void
        if ($this->file_handle !== null) {
            return;
        }

        // use fopen to open the file using the mode given by the abstract class method getFileMode()
        $file_handle = fopen($this->file_name, $this->getFileMode());
        // if there is an error opening the file, throw an exception
        if ($file_handle === false) {
            throw new Exception("Failed to open file $this->file_name");
        }

        // save the file handle for later use as an instance variable
        $this->file_handle = $file_handle;

        // the lock method might throw an exception, so we need to put it in a try/catch block
        try
        {
            $this->lock();
        } catch (Exception $e) {
            // we are giving up on opening the file, so close it
            $this->close();
            // make sure we reset the file_handle to null so we know the file isn't currently open
            $this->file_handle = null;
            throw $e;
        }
    }

    private function lock(): void
    {
        // use flock to lock the file with the lock type given by the abstract class method getLockType()
        $lock_result = flock($this->file_handle, $this->getLockType());
        // if there is an error acquiring the lock, throw an exception
        if ($lock_result === false) {
            throw new Exception("Failed to acquire a lock of type " . $this->getLockType() . " on file $this->file_name");
        }
    }

    public function close(): void
    {
        // we can't close a file that isn't open
        if ($this->file_handle === null) {
            throw new Exception("Cannot close file $this->file_name because it is not currently open.");
        }

        // unlock the file
        flock($this->file_handle, LOCK_UN);
        // close the file
        fclose($this->file_handle);
        // set the file_handle to null so we know that the file isn't currently open
        $this->file_handle = null;
    }

    public function file_end_of_file(): bool
    {
        // use the feof function to determine if the file is at the end or not
        return feof($this->file_handle);
    }

    // if an instantiated class goes out of scope and is garbage collected by PHP without close being called first
    // then we should clean up by closing the file
    function __destruct()
    {
        if ($this->file_handle !== null) {
            $this->close();
        }
    }

    // children classes need to return the proper file mode
    public abstract function getFileMode(): string;

    // children classes need to return the proper lock type
    public abstract function getLockType(): int;
}