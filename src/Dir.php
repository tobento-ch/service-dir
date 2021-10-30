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

/**
 * Dir
 */
class Dir implements DirInterface, Stringable
{
    /**
     * Create a new Dir.
     *
     * @param string|Stringable $dir The directory.
     * @param null|string $name $name A dir name.
     * @param string $group The group.
     * @param int $priority The priority.
     */    
    public function __construct(
        protected string|Stringable $dir,
        protected null|string $name = null,
        protected string $group = 'default',
        protected int $priority = 0,
    ) {
        // Normalize dir.
        $dir = str_replace(['\\', '//'], '/', (string)$dir);
        $this->dir = rtrim($dir, '/').'/';
        
        $this->name = $name ?: $this->dir;
    }
    
    /**
     * Get the dir. Must end with a slash.
     *
     * @return string
     */
    public function dir(): string
    {
        return (string)$this->dir;
    }

    /**
     * Get the name.
     *
     * @return string
     */
    public function name(): string
    {
        return $this->name ?: $this->dir();
    }
    
    /**
     * Get the group.
     *
     * @return string
     */
    public function group(): string
    {
        return $this->group;
    }

    /**
     * Get the priority.
     *
     * @return int
     */
    public function priority(): int
    {
        return $this->priority;
    }
    
    /**
     * Returns the dir.
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->dir();
    }    
}