<?php

namespace Core\Container\Enum;

enum MethodTypes: string
{
    case Object = 'get';
    case Parameter = 'getParam';
    case Tag = 'getByTag';

    public static function getMethodByType(string $type) : MethodTypes
    {
        return match ($type) {
          '@' => self::Object,
          '$' => self::Parameter,
          '&' => self::Tag,
        };
    }
}
