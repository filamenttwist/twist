<?php

namespace Twist\Base;

use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;
use ReflectionClass;

/**
 * Base Data Transfer Object (DTO) for creating structured data containers.
 *
 * This abstract class provides a foundation for all DTOs, offering built-in
 * methods for serialization to arrays and objects using reflection. It helps
 * ensure consistent data structures throughout the application.
 *
 * @package Twist
 * @since 4.0.1
 */
abstract class BaseDTO
{
    /**
     * Converts the DTO instance to an associative array.
     *
     * This method uses reflection to read all public properties of the DTO
     * and returns them as an associative array where keys are property names.
     *
     * @return array<string, mixed> An associative array representation of the DTO.
     *
     * @example
     * ```php
     * $userDto = new UserDTO('John Doe', 30);
     * $userArray = $userDto->toArray();
     * // Returns ['name' => 'John Doe', 'age' => 30]
     * ```
     */
    public function toArray(): array
    {
        $reflection = new ReflectionClass($this);
        $properties = $reflection->getProperties();

        $outProperties = [];

        foreach ($properties as $property) {
            $outProperties[$property->getName()] = $property->getValue($this);
        }

        return $outProperties;
    }

    /**
     * Converts the DTO instance to a standard object (`stdClass`).
     *
     * This provides a generic object representation of the DTO by casting
     * the array version of the object. It is more performant than JSON
     * encoding/decoding.
     *
     * @return object A `stdClass` object representation of the DTO.
     *
     * @example
     * ```php
     * $userDto = new UserDTO('Jane Doe', 25);
     * $userObject = $userDto->toObject();
     * // $userObject->name is 'Jane Doe'
     * ```
     */
    public function toObject(): object
    {
        return (object) $this->toArray();
    }

    /**
     * Creates a DTO instance from an associative array, recursively.
     *
     * This static factory method constructs a new DTO by mapping array keys
     * to the DTO's constructor parameters. It intelligently handles nested DTOs,
     * default values, and throws an exception for missing required parameters.
     *
     * @param array<string, mixed> $data The data to populate the DTO with.
     * @return static A new instance of the DTO.
     * @throws InvalidArgumentException If a required constructor parameter is missing from the data array.
     *
     * @example
     * ```php
     * // For a nested DTO:
     * // $data = ['orderId' => 123, 'customer' => ['name' => 'John', 'email' => 'john@example.com']];
     * // $orderDto = OrderDTO::fromArray($data);
     * // $orderDto->customer will be a CustomerDTO instance.
     * ```
     */
    public static function fromArray(array $data): static
    {
        $reflection = new ReflectionClass(static::class);
        $constructor = $reflection->getConstructor();

        if (!$constructor) {
            return new static();
        }

        $parameters = $constructor->getParameters();
        $args = [];

        foreach ($parameters as $parameter) {
            $name = $parameter->getName();
            $type = $parameter->getType();

            if (array_key_exists($name, $data)) {
                $value = $data[$name];

                // If the parameter is a DTO class and the value is an array, recursively create the nested DTO.
                if (
                    $type instanceof ReflectionNamedType &&
                    !$type->isBuiltin() &&
                    is_array($value) &&
                    is_subclass_of($type->getName(), self::class)
                ) {
                    $args[] = ($type->getName())::fromArray($value);
                } else {
                    $args[] = $value;
                }
            } elseif ($parameter->isDefaultValueAvailable()) {
                $args[] = $parameter->getDefaultValue();
            } elseif ($parameter->allowsNull()) {
                $args[] = null;
            } else {
                throw new InvalidArgumentException("Missing required parameter: \${$name} for " . static::class);
            }
        }

        return new static(...$args);
    }

    /**
     * Creates a DTO instance from an Eloquent Model.
     *
     * This static factory method converts a model instance into an array
     * and then uses the `fromArray` method to construct a new DTO, including
     * any nested DTO structures.
     *
     * @param \Illuminate\Database\Eloquent\Model $model The Eloquent model instance.
     * @return static A new instance of the DTO populated with the model's data.
     *
     * @example
     * ```php
     * $userModel = User::find(1);
     * $userDto = UserDTO::fromModel($userModel);
     * ```
     */
    public static function fromModel(Model $model): static
    {
        return static::fromArray($model->toArray());
    }
}
