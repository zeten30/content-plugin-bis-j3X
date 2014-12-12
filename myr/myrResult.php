<?php

class myrResult {

    protected $type = '';
    protected $data = false;

    public function __construct($type, $data) {
        $this->type = $type;
        $this->data = $data;
    }

    public function __get($name) {
        return $this->$name;
    }

    public function __set($name, $value) {
        $this->$name = $value;
    }

}
