<?php
namespace CoreBundle\Service;

class FormationFileUploader extends FileUploader {
    /**
     * @var strng
     */
    protected $_dir;
    
    /**
     * @param string $dir
     */
    public function __construct($dir) {
        parent::__construct($dir);
    }
}