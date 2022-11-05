<?php

class Request
{
    public $method;
    public $query;
    public $body;

    public function __construct($method, $query, $body)
    {
        $this->method = $method;
        $this->query = $query;
        $this->body = $body;
    }
}