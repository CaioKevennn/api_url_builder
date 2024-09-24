<?php

namespace core;

class Router
{

    private string $url;
    private array $parts;
    private ?string $resource;
    private ?string $id;


    public function __construct()
    {
        $this->url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $this->parts = explode("/", $this->url);
        $this->resource = $this->parts[3] ?? null;
        $this->id = $this->parts[4] ?? null;
    }

    public function getResource() : ?string
    {
        return $this->resource;
    }


    public function getId() : ?string
    {
        return $this->id;
    }


}
