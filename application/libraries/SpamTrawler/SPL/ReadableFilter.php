<?php
/**
 * Created by SpamTrawler.
 * User: griddie
 * Date: 15/04/15
 * Time: 11:13
 * Copyright (c) 2014 Oliver Putzer (SpamTrawler) 
 */

class SpamTrawler_SPL_ReadableFilter extends RecursiveFilterIterator {
    public function accept(){
        return $this->current()->isReadable();
    }
}