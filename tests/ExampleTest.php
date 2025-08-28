<?php
use PHPUnit\Framework\TestCase;

class ExampleTest extends TestCase
{
    public function testYiiVersionIsString(): void
    {
        $this->assertIsString(\Yii::getVersion());
    }
}
