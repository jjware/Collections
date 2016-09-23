<?php

namespace Collections;

class CollectionTest extends \PHPUnit_Framework_TestCase
{
    public function testAdd()
    {
        $collection = new Collection();

        $collection->add('one');

        $this->assertEquals(new Collection(['one']), $collection);

        $collection->add('two');

        $this->assertEquals(new Collection(['one', 'two']), $collection);

        $collection->add('three');

        $this->assertEquals(new Collection(['one', 'two', 'three']), $collection);
    }

    public function testClear()
    {
        $collection = new Collection(['one', 'two', 'three']);

        $collection->clear();

        $this->assertEquals(new Collection([]), $collection);
    }

    public function testCopyToDefaultBehavior()
    {
        $collection = new Collection(['one', 'two', 'three']);

        $array = [];

        $collection->copyTo($array);

        $this->assertEquals(['one', 'two', 'three'], $array);
    }

    public function testCopyToWithIndexInMiddle()
    {
        $collection = new Collection(['one', 'two', 'three']);

        $array = ['testing', 'testing', 'now', 'done'];

        $collection->copyTo($array, 2);

        $this->assertEquals(['testing', 'testing', 'one', 'two', 'three'], $array);
    }

    public function testCopyToWithIndexAtEnd()
    {
        $collection = new Collection(['one', 'two', 'three']);

        $array = ['testing', 'testing'];

        $collection->copyTo($array, count($array));

        $this->assertEquals(['testing', 'testing', 'one', 'two', 'three'], $array);
    }

    /**
     * @expectedException \Collections\InvalidArgumentException
     */
    public function testCopyToIndexLessThanZero()
    {
        $array = ['one', 'two', 'three'];

        $collection = new Collection();

        $collection->copyTo($array, -1);
    }

    /**
     * @expectedException \Collections\InvalidArgumentException
     */
    public function testCopyToIndexNan()
    {
        $array = ['one', 'two', 'three'];

        $collection = new Collection();

        $collection->copyTo($array, 'j');
    }

    public function testExists()
    {
        $collection = new Collection(['one', 'two', 'three']);

        $result = $collection->exists(function ($object) {
            return strlen($object) > 3;
        });

        $this->assertTrue($result);
    }

    public function testExistsWhenNot()
    {
        $collection = new Collection(['one', 'two', 'three']);

        $result = $collection->exists(function ($object) {
            return $object === 'four';
        });

        $this->assertFalse($result);
    }

    public function testFind()
    {
        $list = new Collection([1, 2, 3, 4, 5, 6]);

        $result = $list->find(function ($object) {
            return $object > 4;
        });

        $this->assertEquals(5, $result);

        $result = $list->find(function ($object) {
            return $object === "3";
        });

        $this->assertEquals(null, $result);
    }

    public function testFindAll()
    {
        $list = new Collection([1, 2, 3, 4, 5, 6]);

        $result = $list->findAll(function ($object) {
            return $object > 3;
        });

        $this->assertEquals([4, 5, 6], $result->toArray());

        $result = $list->findAll(function ($object) {
            return $object === "3";
        });

        $this->assertEquals([], $result->toArray());
    }

    public function testForEach()
    {
        $array = ["one", "two", "three"];
        $list = new Collection($array);
        $ctr = 0;

        foreach ($list as $key => $value) {
            $this->assertEquals($ctr, $key);
            $this->assertEquals($array[$ctr], $value);
            $ctr++;
        }
    }

    public function testGetItem()
    {
        $list = new Collection();
        $list->add(1);

        $this->assertEquals($list[0], 1);
    }

    /**
     * @expectedException \Collections\ArgumentOutOfRangeException
     */
    public function testGetItemOutOfBounds()
    {
        $list = new Collection();
        $list[0];
    }

    /**
     * @expectedException \Collections\InvalidArgumentException
     */
    public function testGetItemInvalidArgument()
    {
        $list = new Collection();
        $list['j'];
    }

    public function testSetItem()
    {
        $list = new Collection();
        $list->add(1);
        $list[0] = 2;

        $this->assertEquals($list[0], 2);
    }

    /**
     * @expectedException \Collections\ArgumentOutOfRangeException
     */
    public function testSetItemOutOfBounds()
    {
        $list = new Collection();
        $list[0] = 2;
    }

    /**
     * @expectedException \Collections\InvalidArgumentException
     */
    public function testSetItemInvalidArgument()
    {
        $list = new Collection();
        $list->add(1);
        $list['j'] = 2;
    }

    public function testRemove()
    {
        $list = new Collection();
        $list->add("one");
        $list->add("two");
        $list->remove("one");

        $this->assertEquals(["two"], $list->toArray());
    }

    public function testRemoveAt()
    {
        $list = new Collection();
        $list->add("one");
        $list->add("two");
        $list->add("three");

        $list->removeAt(1);

        $this->assertEquals(["one", "three"], $list->toArray());
    }

    /**
     * @expectedException \Collections\InvalidArgumentException
     */
    public function testRemoveAtInvalidArgument()
    {
        $list = new Collection();
        $list->removeAt('j');
    }

    /**
     * @expectedException \Collections\ArgumentOutOfRangeException
     */
    public function testRemoveAtOutOfRange()
    {
        $list = new Collection();
        $list->removeAt(1);
    }

    public function testRemoveAll()
    {
        $list = new Collection([1, 2, 3, 4, 3, 5, 3]);

        $list->removeAll(function ($object) {
            return $object === 3;
        });

        $this->assertEquals([1, 2, 4, 5], $list->toArray());
    }

    public function testRemoveRange()
    {
        $list = new Collection([1, 2, 3, 4, 5, 6]);
        $list->removeRange(0, 4);

        $this->assertEquals([5, 6], $list->toArray());

        $list = new Collection([1, 2, 3, 4, 5, 6]);
        $list->removeRange(3, 3);

        $this->assertEquals([1, 2, 3], $list->toArray());

        $list = new Collection([1, 2, 3, 4, 5, 6]);
        $list->removeRange(2, 2);

        $this->assertEquals([1, 2, 5, 6], $list->toArray());
    }

    public function testInsert()
    {
        $list = new Collection();
        $list->add("one");
        $list->add("two");

        $list->insert(1, "three");

        $this->assertEquals(["one", "three", "two"], $list->toArray());
    }

    /**
     * @expectedException \Collections\InvalidArgumentException
     */
    public function testInsertInvalidArgument()
    {
        $list = new Collection();
        $list->insert('j', "one");
    }

    /**
     * @expectedException \Collections\ArgumentOutOfRangeException
     */
    public function testInsertOutOfRange()
    {
        $list = new Collection();
        $list->insert(4, "one");
    }

    public function testInsertPlusOne()
    {
        $list = new Collection();
        $list->add("one");

        $list->insert(1, "two");

        $this->assertEquals(["one", "two"], $list->toArray());
    }

    public function testIndexOf()
    {
        $list = new Collection();
        $list->add("one");
        $list->add("two");
        $list->add("three");

        $this->assertEquals(1, $list->indexOf("two"));
        $this->assertEquals(-1, $list->indexOf("four"));
    }

    public function testSortNumeric()
    {
        $list = new Collection();
        $list->add(2);
        $list->add(5);
        $list->add(3);
        $list->add(1);
        $list->add(4);

        $list->sort();

        $this->assertEquals([1, 2, 3, 4, 5], $list->toArray());
    }

    public function testSortAlphaNumeric()
    {
        $list = new Collection();
        $list->add("img12.png");
        $list->add("img2.png");
        $list->add("img10.png");
        $list->add("img1.png");

        $list->sort();

        $this->assertEquals(["img1.png", "img10.png", "img12.png", "img2.png"], $list->toArray());
    }

    public function testSortWithNatural()
    {
        $list = new Collection();
        $list->add("img12.png");
        $list->add("img2.png");
        $list->add("img10.png");
        $list->add("img1.png");
        $list->sort(new NaturalStringComparer());

        $this->assertEquals(["img1.png", "img2.png", "img10.png", "img12.png"], $list->toArray());
    }
}
