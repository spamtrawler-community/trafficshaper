<?php

class SpamTrawler_Debug {
    public $mData, $sFilename, $iLine, $sClass, $sMethod, $bExit = false;

    public function show(){
        if(!empty($this->mData)){
            print("<pre>".print_r($this->mData,true)."</pre><br />");
        }
        if(!empty($this->sFilename)){
        echo 'File: ' . $this->sFilename . '<br />';
        }

        if(!empty($this->iLine)){
        echo 'Line: ' . $this->iLine . '<br />';
        }

        if(!empty($this->sClass)){
        echo 'Class: ' . $this->sClass . '<br />';
        }

        if(!empty($this->sMethod)){
        echo 'Method: ' . $this->sMethod . '<br />';
        }

        if($this->bExit){
            die();
        }
    }
}
