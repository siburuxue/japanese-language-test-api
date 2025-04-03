<?php

namespace App\Lib\Tool;

class ArrayTool
{
    public static function getEmptyAssociativeArray(): object
    {
        return new class(){};
    }
}
