<?php


namespace App\Models\Enum;

use ReflectionClass;
use ReflectionException;

class BasicEnum
{
    private static $constCacheArray = NULL;

    /**
     * @return mixed
     * @throws ReflectionException
     */
    public static function getConstants()
    {
        if (self::$constCacheArray == NULL) {
            self::$constCacheArray = [];
        }
        $calledClass = get_called_class();
        if (!array_key_exists($calledClass, self::$constCacheArray)) {
            $reflect = new ReflectionClass($calledClass);
            self::$constCacheArray[$calledClass] = $reflect->getConstants();
        }
        return self::$constCacheArray[$calledClass];
    }

    public static function getName($value)
    {
        if (self::isValidValue($value)) {
            $names = array_flip(self::getConstants());
            return $names[$value];
        }
        return 'Value not found';
    }

    public static function getValue($name)
    {
        if (self::isValidName($name)) {
            $values = self::getConstants();
            return $values[$name];
        }
        return 'Name not found';
    }

    public static function isValidName($name, $strict = false)
    {
        $constants = self::getConstants();

        if ($strict) {
            return array_key_exists($name, $constants);
        }

        $keys = array_map('strtolower', array_keys($constants));
        return in_array(strtolower($name), $keys);
    }

    public static function isValidValue($value, $strict = true)
    {
        $values = array_values(self::getConstants());
        return in_array($value, $values, $strict);
    }
}
