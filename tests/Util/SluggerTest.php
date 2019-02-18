<?php

namespace App\Tests\Util;

use App\Util\Slugger;
use PHPUnit\Framework\TestCase;

class SluggerTest extends TestCase
{

    public function testSlug()
    {
        $result = Slugger::sluggify('Przykładowy teskt 9');
        $this->assertEquals('przykladowy-teskt-9', $result);
    }
}
