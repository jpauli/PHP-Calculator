<?php
use Calculator\Memory\MemoryInterface;
use Calculator\Calculator;
use Calculator\Memory\MemorySlots;
use PHPUnit\Framework\TestCase;

class CalculatorTest extends TestCase
{
    private Calculator $calculator;
    private MemoryInterface $mock;

    public function setUp() : void
    {
        $this->mock       = $this->createMock(MemoryInterface::class);
        $this->calculator = new Calculator($this->mock);
    }

    public function assertPreConditions() : void
    {
        $this->assertEquals(0, $this->calculator->loadM1());
        $this->assertEquals(0, $this->calculator->loadM2());
    }

    public function testAdd()
    {
        $this->assertEquals(10, $this->calculator->add(5, 5));
        $this->assertEquals(0, $this->calculator->add(5, -5));
    }

    public function testSub()
    {
        $this->assertEquals(5, $this->calculator->sub(10, 5));
    }

    public function testMult()
    {
        $this->assertEquals(6, $this->calculator->mult(3, 2));
    }

    public function testDiv()
    {
        $this->assertEquals(8, $this->calculator->div(16, 2));
    }

    public function testDivByZero()
    {
        $this->assertEquals(INF, $this->calculator->div(10, 0));
    }

    public function testSimpleSaveMemory()
    {
        $this->calculator->add(4, 4);

        $this->mock->expects($this->once())->method("save")
                                            ->with(MemorySlots::MEMORY_SLOT_ONE, 8);

        $this->mock->expects($this->once())->method("load")
                                            ->with(MemorySlots::MEMORY_SLOT_ONE)
                                            ->will($this->returnValue(8));

        $this->calculator->saveM1();
        $this->assertEquals(8, $this->calculator->loadM1());
    }

    public function testTwoMemorySlots()
    {
        $this->calculator->add(4,4);
        $this->mock->expects($this->exactly(2))->method("save")->withConsecutive([MemorySlots::MEMORY_SLOT_ONE, 8], [MemorySlots::MEMORY_SLOT_TWO, 2]);

        $this->calculator->saveM1();
        $this->calculator->div(10, 5);
        $this->calculator->saveM2();

        $this->mock->expects($this->exactly(2))
                    ->method("load")
                    ->willReturn(8.0, 2.0);
        $this->assertEquals(8, $this->calculator->loadM1());
        $this->assertEquals(2, $this->calculator->loadM2());
    }

    public function testMemoryUsage()
    {
        $this->mock->expects($this->exactly(2))->method("save")->withConsecutive([MemorySlots::MEMORY_SLOT_ONE, 10], [MemorySlots::MEMORY_SLOT_TWO, 200]);
        $this->calculator->sub(20, 10);
        $this->calculator->saveM1();
        $this->calculator->mult(20, 10);
        $this->calculator->saveM2();

        $this->mock->expects($this->exactly(2))->method("load")
                                           ->willReturn(200.0, 10.0);
        $this->assertEquals(210,
             $this->calculator->add($this->calculator->loadM1(),
                                       $this->calculator->loadM2()));
    }
}