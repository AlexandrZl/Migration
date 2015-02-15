<?php

class MathTest extends PHPUnit_Framework_TestCase
{
    public function testOnLessThanZero()
    {
        $math = new Math(-10);
        $result = $math->calculate();

        $this->assertEquals(100, $math->getResult());
    }

    public function testOnMoreThanZero()
    {
        $math = new Math(10);
        $result = $math->calculate();

        $this->assertEquals(1000, $math->getResult());
    }

    public function testOnMoreThan100()
    {
        $math = new Math(120);
        $result = $math->calculate();

        $this->assertEquals(60120, $math->getResult());
    }

    public function testOnString()
    {
        $math = new Math('0');
        $result = $math->calculate();

        $this->assertEquals(0, $math->getResult());
    }

    public function testOnFloat()
    {
        $math = new Math(1.1);
        $result = $math->calculate();

        $this->assertEquals(100, $math->getResult());
    }
}