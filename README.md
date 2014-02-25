# collection

Library that offer multiple implementations of collection.

## Supported collection


### ArrayCollection

Wraps the array behind a class to control what can be added and what can't.

Usage:

    $collection = new ArrayCollection();
    $collection->add(2);
    $collection->add(3);

    foreach ($collection as $element) {
        echo $element;
    }

    /**
     * Print
     * 2
     * 3
     */

### Typed Collection

Encapsulate the constraint to add a specific class type.

Usage:

    $collection = new TypedCollection('\stdClass');
    $collection->add(2); // Throw exception
    $collection->add(new \stdClass()); // works
