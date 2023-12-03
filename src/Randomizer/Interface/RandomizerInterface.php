<?php

namespace App\Randomizer\Interface;

interface RandomizerInterface
{
    public function randomize() : string;
    public function setLength(int $length) : void;
}