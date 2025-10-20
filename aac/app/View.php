<?php
namespace App;

class View
{
    public static function make(string $name, array $data = []): string
    {
        return view($name, $data);
    }
}
