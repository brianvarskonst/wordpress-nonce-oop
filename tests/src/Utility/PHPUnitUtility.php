<?php

namespace Bvsk\WordPress\NonceManager\Tests\Utility;

use BadMethodCallException;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;

/**
 * Class PHPUnitUtility
 *
 * @package NoncesManager\Tests\Utility
 */
class PHPUnitUtility
{
    /**
     * Call protected or private setter Method in Class
     *
     * @param object $class The Class to reflect
     * @param string $setterName Setter Method Name
     * @param array $setterArgs Setter Method Arguments
     *
     * @return ReflectionClass          New Reflection Class Instance
     *
     * @throws BadMethodCallException   Throws this exception if the getter method not exists in this class
     */
    public static function callSetterMethod($class, string $setterName, string $setterArgs): ?ReflectionClass
    {
        try {
            $reflectionClassInstance = new ReflectionClass($class);
            $method = $reflectionClassInstance->getMethod($setterName);
            $method->setAccessible(true);

            $method->invoke($class, $setterArgs);

            return $reflectionClassInstance;
        } catch (ReflectionException $e) {
            throw new BadMethodCallException("method '$setterName' does not exist");
        }
    }

    /**
     * Call protected or private getter Method in Class
     *
     * @param ReflectionClass $reflectionClassInstance  The Reflection Class Method - instantiated at the callSetterMethod
     * @param $class
     * @param string            $getterName             Getter Method Name
     *
     * @return mixed                            Return the Method Value
     *
     */
    public static function callGetterMethod(ReflectionClass $reflectionClassInstance, $class, string $getterName)
    {
        try {
            $returnValue = $reflectionClassInstance->getMethod($getterName);
            $returnValue = $returnValue->invoke($class);

            return $returnValue;
        } catch (ReflectionException $e) {
            throw new BadMethodCallException("method '$getterName' does not exist");
        }
    }
}