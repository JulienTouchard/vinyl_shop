<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;

class VinylShopTest extends TestCase {
    public function testRun(){
        $resultat = 2;
        $this->assertEquals($resultat, 1+2,'Oups ca marche pas');
    }
}