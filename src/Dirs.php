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

namespace Tobento\Service\Dir;

use Stringable;
use IteratorAggregate;
use ArrayIterator;

/**
 * Dirs
 */
class Dirs implements DirsInterface, IteratorAggregate
{
    /**
     * @var array<string, DirInterface>
     */    
    protected array $dirs = [];
    
    /**
     * Create a new Dirs.
     *
     * @param array<string, DirInterface> $dirs
     */    
    public function __construct(
        DirInterface ...$dirs,
    ) {
        foreach($dirs as $dir)
        {
            $this->add($dir);
        }
    }
    
    /**
     * Adds a directory.
     *
     * @param DirInterface $dir
     * @return static $this
     */
    public function add(DirInterface $dir): static
    {        
        $this->dirs[$dir->name()] = $dir;
        return $this;
    }
        
    /**
     * Adds a directory.
     *
     * @param string|Stringable $dir The directory.
     * @param null|string $name $name A dir name.
     * @param string $group The group.
     * @param int $priority The priority.
     * @return static $this
     */
    public function dir(
        string|Stringable $dir,
        null|string $name = null,
        string $group = 'default',
        int $priority = 0,
    ): static {
        $this->add(new Dir($dir, $name, $group, $priority));
        return $this;
    }

    /**
     * Returns a new instance with the dirs filtered.
     *
     * @param callable $callback
     * @return static
     */
    public function filter(callable $callback): static
    {
        $new = clone $this;
        $new->dirs = array_filter($this->dirs, $callback);
        return $new;
    }
    
    /**
     * Returns a new instance with the specified group.
     *
     * @param string $group The group name.
     * @return static
     */
    public function group(string $group): static
    {
        return $this->filter(fn(DirInterface $dir): bool => $dir->group() === $group);
    }
    
    /**
     * Returns a new instance with the specified groups.
     *
     * @param array<int, string> $groups The group names.
     * @return static
     */
    public function groups(array $groups): static
    {
        return $this->filter(fn(DirInterface $dir): bool => in_array($dir->group(), $groups));
    }    

    /**
     * Returns a new instance with only the dirs specified.
     *
     * @param array<int, string> $names
     * @return static
     */
    public function only(array $names): static
    {
        return $this->filter(fn(DirInterface $dir): bool => in_array($dir->name(), $names));
    }
    
    /**
     * Returns a new instance with all dirs except those specified.
     *
     * @param array<int, string> $names
     * @return static
     */
    public function except(array $names): static
    {
        return $this->filter(fn(DirInterface $dir): bool => !in_array($dir->name(), $names));
    }
    
    /**
     * Returns a new instance with the dirs sorted.
     *
     * @param null|callable $callback If null, sorts by priority, highest first.
     * @return static
     */    
    public function sort(null|callable $callback = null): static
    {
        if (is_null($callback))
        {
            $callback = fn(DirInterface $a, DirInterface $b): int
                => $b->priority() <=> $a->priority();
        }
        
        $new = clone $this;
        uasort($new->dirs, $callback);
        return $new;
    }    
    
    /**
     * Returns all dirs.
     *
     * @return array<string, DirInterface>
     */
    public function all(): array
    {        
        return $this->dirs;
    }

    /**
     * Returns true if dir exists, otherwise false.
     *
     * @param string $name
     * @return bool
     */
    public function has(string $name): bool
    {
        return array_key_exists($name, $this->dirs);
    }
        
    /**
     * Returns the dir by its name.
     *
     * @param string $name
     * @return DirInterface
     *
     * @throws DirNotFoundException
     */
    public function getDir(string $name): DirInterface
    {
        if (!$this->has($name))
        {
            throw new DirNotFoundException(
                $name,
                'Dir ['.$name.'] not found!'
            );
        }

        return $this->dirs[$name];
    }
    
    /**
     * Returns the dir (string) by its name.
     *
     * @param string $name
     * @return string
     *
     * @throws DirNotFoundException
     */
    public function get(string $name): string
    {
        return $this->getDir($name)->dir();
    }

    /**
     * Returns an iterator for the dirs.
     *
     * @return ArrayIterator
     */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->all());
    }    
}