<?php

namespace Core\Interface;

interface SingletonInterface
{
    public static function getInstance() : self;
}