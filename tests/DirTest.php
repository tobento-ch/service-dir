<?php

/**
 * TOBENTO
 *
 * @copyright   Tobias Strub, TOBENTO
 * @license     MIT License, see LICENSE file distributed with this source code.
 * @author      Tobias Strub
 * @link        https://www.tobento.ch
 */

declare(strict_types=1);

namespace Tobento\Service\Dir\Test;

use PHPUnit\Framework\TestCase;
use Tobento\Service\Dir\Dir;
use Tobento\Service\Dir\DirInterface;

/**
 * DirTest tests
 */
class DirTest extends TestCase
{
    public function testCreateDir()
    {
        $dir = new Dir('home/private/views');
        
        $this->assertInstanceOf(DirInterface::class, $dir);
    }
    
    public function testDirMethod()
    {
        $dir = new Dir('home/private/views/');
        
        $this->assertSame('home/private/views/', $dir->dir());
    }
    
    public function testDirMethodMustReturnDirWithSlash()
    {
        $dir = new Dir('home/private/views');
        
        $this->assertSame('home/private/views/', $dir->dir());
    }

    public function testNameMethod()
    {
        $dir = new Dir('home/private/views');
        
        $this->assertSame('home/private/views/', $dir->name());
        
        $dir = new Dir('home/private/views', name: 'views');
        
        $this->assertSame('views', $dir->name());
    }
    
    public function testGroupMethod()
    {
        $dir = new Dir('home/private/views');
        
        $this->assertSame('default', $dir->group());
        
        $dir = new Dir('home/private/views', group: 'backend');
        
        $this->assertSame('backend', $dir->group());
    }
    
    public function testPriorityMethod()
    {
        $dir = new Dir('home/private/views');
        
        $this->assertSame(0, $dir->priority());
        
        $dir = new Dir('home/private/views', priority: 5);
        
        $this->assertSame(5, $dir->priority());
    }    
}