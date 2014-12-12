<?php

//Template data class
class myrTemplateData {

    protected $head = "";
    protected $item = "";
    protected $foot = "";
    protected $link_detail = "";

    public function __get($name) {
        return $this->$name;
    }

    public function __set($name, $value) {
        $this->$name = $value;
        return true;
    }

}
