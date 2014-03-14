# collection

Library that offer multiple implementations of collection.

## Installation

To install the package using [composer] (https://getcomposer.org/), you just need to add the following lines to your `composer.json` file.

    ...
    "require": {
        "star/collection": "~1.1"
    }
    ...

## Supported collection

### Enumeration

Wrap an immutable array of values in an object. Ensure that any value passed to the enumeration is supported by the instance.

    $enumeration = new Enumeration(array(1,2,3));
    $enumeration->getSelected(); // returns null
    $enumeration->select(2);
    $enumeration->getSelected(); // returns 2
    $enumeration->select('invalid'); // Throw Exception

### Typed Collection

Encapsulate the constraint to add a specific class type.

Usage:

    $collection = new TypedCollection('\stdClass');
    $collection->add(2); // Throw exception
    $collection->add(new \stdClass()); // works

Note: This class can be used in conjunction with [doctrine/collections](https://github.com/doctrine/collections) as an added functionality.
