<?php
/**
 * Created by PhpStorm.
 * User: wangxudong is good body
 * Date: 2018/9/25
 * Time: 16:54
 */
namespace Core;

class Error extends \Exception {
    public function errorMessage()
    {
        $errorMsg = "<h2>:(</h2>".
            "Error on line :" . $this->getLine() ." in ".$this->getFile() ."<h4>" .$this->getMessage()."</h4>";
        exit($errorMsg);
    }

}