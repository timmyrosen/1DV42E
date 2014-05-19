<?php namespace Models;

class Validate {

    /**
     * Check if string is empty
     * @param   string   $string
     * @return  boolean
     */
    public function isEmpty($string) {
        return empty($string);
    }

}