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
use Tobento\Service\Dir\Dirs;
use Tobento\Service\Dir\DirsInterface;
use Tobento\Service\Dir\Dir;
use Tobento\Service\Dir\DirInterface;
use Tobento\Service\Dir\DirNotFoundException;

/**
 * DirsTest tests
 */
class DirsTest extends TestCase
{
    public function testCreateDirs()
    {
        $dirs = new Dirs();
        
        $this->assertInstanceOf(DirsInterface::class, $dirs);
    }

    public function testAddMethod()
    {
        $dirs = new Dirs();
        
        $dir = new Dir('home/private/views', 'views');
        $dirConfig = new Dir('home/private/config', 'config');
        
        $dirs->add($dir)
             ->add($dirConfig);
        
        $this->assertSame($dir, $dirs->all()['views']);
        $this->assertSame($dirConfig, $dirs->all()['config']);
    }
    
    public function testDirMethod()
    {
        $dirs = new Dirs();
        
        $dirs->dir(
            dir: 'home/private/config',
            name: 'config',
            group: 'front',
            priority: 10,
        );
        
        $this->assertSame('home/private/config/', $dirs->all()['config']->dir());
        $this->assertSame('config', $dirs->all()['config']->name());
        $this->assertSame('front', $dirs->all()['config']->group());
        $this->assertSame(10, $dirs->all()['config']->priority());
    }    

    public function testAllMethod()
    {
        $dirs = new Dirs();
        $dirs->dir('home/private/views/front');
        $dirs->dir('home/private/views/back');
        
        $this->assertSame(2, count($dirs->all()));
    }
    
    public function testHasMethod()
    {
        $dirs = new Dirs();
        $dirs->dir('home/private/views', 'views');
        
        $this->assertTrue($dirs->has('views'));
        $this->assertFalse($dirs->has('config'));
    }
    
    public function testGetMethod()
    {
        $dirs = new Dirs();
        $dir = new Dir('home/private/views', 'views');
        $dirs->add($dir);
        
        $this->assertSame('home/private/views/', $dirs->get('views'));
    }
    
    public function testGetMethodThrowsDirNotFoundException()
    {
        $this->expectException(DirNotFoundException::class);
        
        $dirs = new Dirs();
        $dirs->get('views');
    }    
    
    public function testGetDirMethod()
    {
        $dirs = new Dirs();
        $dir = new Dir('home/private/views', 'views');
        $dirs->add($dir);
        
        $this->assertSame($dir, $dirs->getDir('views'));
        
        $this->assertInstanceOf(
            DirInterface::class,
            $dirs->getDir('views')
        );
    }
    
    public function testGetDirMethodThrowsDirNotFoundException()
    {
        $this->expectException(DirNotFoundException::class);
        
        $dirs = new Dirs();
        $dirs->getDir('views');
    }    
    
    public function testFilterMethod()
    {
        $dirs = new Dirs();
        $dirs->dir(dir: 'home/private/views/front', group: 'front');
        $dirs->dir(dir: 'home/private/views/back', group: 'back');
        
        $dirsNew = $dirs->filter(fn(DirInterface $dir): bool => $dir->group() === 'front');
        
        $this->assertFalse($dirs === $dirsNew);
        
        $this->assertSame(1, count($dirsNew->all()));
    }
    
    public function testGroupMethod()
    {
        $dirs = new Dirs();
        $dirs->dir(dir: 'home/private/views/front', group: 'front');
        $dirs->dir(dir: 'home/private/views/back', group: 'back');
        
        $dirsNew = $dirs->group('front');
        
        $this->assertFalse($dirs === $dirsNew);
        
        $this->assertSame(1, count($dirsNew->all()));
    }
    
    public function testGroupsMethod()
    {
        $dirs = new Dirs();
        $dirs->dir(dir: 'home/private/views/front', group: 'front');
        $dirs->dir(dir: 'home/private/views/back', group: 'back');
        $dirs->dir(dir: 'home/private/config', group: 'config');
        
        $dirsNew = $dirs->groups(['front', 'config']);
        
        $this->assertFalse($dirs === $dirsNew);
        
        $this->assertSame(2, count($dirsNew->all()));
    }
    
    public function testOnlyMethod()
    {
        $dirs = new Dirs();
        $dirs->dir(dir: 'home/private/views', name: 'views');
        $dirs->dir(dir: 'home/private/cache', name: 'cache');
        $dirs->dir(dir: 'home/private/config', name: 'config');
        
        $dirsNew = $dirs->only(['views', 'config']);
        
        $this->assertFalse($dirs === $dirsNew);
        
        $this->assertTrue($dirsNew->has('views'));
        $this->assertTrue($dirsNew->has('config'));
        $this->assertFalse($dirsNew->has('cache'));
    } 

    public function testExceptMethod()
    {
        $dirs = new Dirs();
        $dirs->dir(dir: 'home/private/views', name: 'views');
        $dirs->dir(dir: 'home/private/cache', name: 'cache');
        $dirs->dir(dir: 'home/private/config', name: 'config');
        
        $dirsNew = $dirs->except(['views', 'config']);
        
        $this->assertFalse($dirs === $dirsNew);
        
        $this->assertFalse($dirsNew->has('views'));
        $this->assertFalse($dirsNew->has('config'));
        $this->assertTrue($dirsNew->has('cache'));
    }

    public function testSortMethod()
    {
        $dirs = new Dirs();
        $dirs->dir(dir: 'home/private/views', name: 'views', priority: 10);
        $dirs->dir(dir: 'home/private/cache', name: 'cache', priority: 5);
        $dirs->dir(dir: 'home/private/config', name: 'config', priority: 15);
        
        $dirsNew = $dirs->sort();
        
        $this->assertFalse($dirs === $dirsNew);
        
        $this->assertSame(
            ['config', 'views', 'cache'],
            array_keys($dirsNew->all())
        );
    } 
    
    public function testSortMethodWithCallback()
    {
        $dirs = new Dirs();
        $dirs->dir(dir: 'home/private/views', name: 'views');
        $dirs->dir(dir: 'home/private/cache', name: 'cache');
        $dirs->dir(dir: 'home/private/config', name: 'config');
        
        $dirsNew = $dirs->sort(
            fn(DirInterface $a, DirInterface $b): int => $a->name() <=> $b->name()
        );
        
        $this->assertFalse($dirs === $dirsNew);
        
        $this->assertSame(
            ['cache', 'config', 'views'],
            array_keys($dirsNew->all())
        );
    }    
}