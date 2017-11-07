<?php
namespace CoreBundle\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader {
    /**
     * @var string
     */
    protected $_dir;
    
    /**
     * @param string $dir
     */
    public function __construct($dir) {
        $this->_dir = $dir;
    }
    
    /**
     * @param UploadedFile $file
     * @return string
     */
    public function upload(UploadedFile $file) {
        $fileName = md5(uniqid()).'.'.$file->guessExtension();

        $file->move($this->_dir, $fileName);

        return $fileName;
    }
    
    /**
     * @return string
     */
    public function getDir() {
        return $this->_dir;
    }
}