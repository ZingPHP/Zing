<?php

namespace Modules;

class File extends Module{

    protected $filename = "";

    /**
     * Sets the file that the class will work with
     * @param string $filename
     */
    public function setFile($filename){
        $this->filename = $filename;
    }

    /**
     * Truncates a file in the file system
     * @param string $filename
     */
    public function truncate($filename = null){
        $filename = $this->getFile($filename);
        file_put_contents($filename, "");
    }

    /**
     * Deletes a file from the file system.
     * @param string $filename
     */
    public function delete($filename = null){
        $filename = $this->getFile($filename);
        unlink($filename);
    }

    /**
     * Creates a file in the file system.
     * By default this is set to the new class default file.
     * @param string $filename
     * @param boolean $setFile
     */
    public function create($filename, $setFile = true){
        if((bool)$setFile){
            $this->setFile($filename);
        }
        touch($filename);
    }

    /**
     * Creates a copy of a file, if no source is given the current
     * class default file is used.
     * @param string $destination
     * @param string $source
     */
    public function copy($destination, $source = null){
        $filename = $this->getFile($filename);
        $content  = file_get_contents($source);
        file_put_contents($destination, $content);
    }

    /**
     * Saves content to a file.
     * By default the current class file is used unless one is provided.
     * @param string $content
     * @param string $filename
     */
    public function save($content, $filename = null){
        $filename = $this->getFile($filename);
        file_put_contents($filename, $content);
    }

    /**
     * Reads the contents of a file.
     * @param string $filename
     * @return string
     */
    public function read($filename = null){
        $filename = $this->getFile($filename);
        return file_get_contents($filename);
    }

    /**
     * Appends content to the end of a file.
     * @param string $content
     * @param string $filename
     */
    public function append($content, $filename = null){
        $filename = $this->getFile($filename);
        $content1 = file_get_contents($filename);
        file_put_contents($filename, $content1 . $content);
    }

    /**
     * Prepends content to the beginning of a file.
     * @param string $content
     * @param string $filename
     */
    public function prepend($content, $filename = null){
        $filename = $this->getFile($filename);
        $content1 = file_get_contents($filename);
        file_put_contents($filename, $content . $content1);
    }

    /**
     * Creates a directory recursivly allowing for creation of nested directories.
     * @param string $pathname
     * @param int $mode
     */
    public function mkdir($pathname, $mode = 777){
        mkdir($pathname, $mode, true);
    }

    protected function getFile($filename){
        if($filename === null){
            $filename = $this->filename;
        }
        return $filename;
    }

}
