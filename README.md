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

#### Basic usage

    $collection = new TypedCollection('\stdClass');
    $collection->add(2); // Throw exception
    $collection->add(new \stdClass()); // works

    $collection = new TypedCollection('\Countable');
    $collection->add(2); // Throw exception
    $collection->add(new ClassThatImplementsCountable()); // works

#### Advanced usage

##### Custom class

Lets say that you want a `Car` collection, you could just define it using the basic usage, but it would lead to code
duplication. So a good practice would be to define a new class named `CarCollection`, and use composition instead of
inheritance, and declare it like this:

    class CarCollection
    {
        private $collection;

        public function __construct()
        {
            $this->collection = new TypedCollection('tests\Star\Component\Collection\Example\Car');
        }
    }

Declaring your collection like this will enable you to encapsulate logic relevant to the car collection at one place,
instead of risking to expose the inner implementation to the outside world. That way, you can control what methods are
available and avoid duplication.

Using this example, adding a `Car` to the collection would be easy by implementing the `addCar` method.

    class CarCollection
    {
        ...
        public function addCar(Car $car)
        {
            $this->collection->add($car);
        }
        ...
    }

And, if you want to filter all the cars based on their color, you can internally use it like this:

    class CarCollection
    {
        ...
        public function findAllCarWithColor(Color $color)
        {
            $closure = function(Car $car) use ($color) {
                return $car->getColor() == $color;
            };

            return $this->collection->filter($closure)->toArray();
        }
        ...
    }

The same could also be done for finding cars based on their name:

    class CarCollection
    {
        ...
        public function findAllWithName($name)
        {
            $expression = Criteria::expr()->eq('name', $name);
            $criteria = new Criteria($expression);

            return $this->collection->matching($criteria)->toArray();
        }
        ...
    }

From now on, your collection is re-usable, and testable at one place, while avoiding the pitfalls of inheritance.

Note: This class can be used in conjunction with [doctrine/collections](https://github.com/doctrine/collections) as an
added functionality, but always keep in mind that the custom collection should implement the `Doctrine\Common\Collections\Collection`.

