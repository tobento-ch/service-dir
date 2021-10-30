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
 * DirsInterface
 */
interface DirsInterface
{
    /**
     * Adds a directory.
     *
     * @param DirInterface $dir
     * @return static $this
     */
    public function add(DirInterface $dir): static;
        
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
    ): static;

    /**
     * Returns a new instance with the dirs filtered.
     *
     * @param callable $callback
     * @return static
     */
    public function filter(callable $callback): static;
    
    /**
     * Returns a new instance with the specified group.
     *
     * @param string $group The group name.
     * @return static
     */
    public function group(string $group): static;
    
    /**
     * Returns a new instance with the specified groups.
     *
     * @param array<int, string> $groups The group names.
     * @return static
     */
    public function groups(array $groups): static;    

    /**
     * Returns a new instance with only the dirs specified.
     *
     * @param array<int, string> $names
     * @return static
     */
    public function only(array $names): static;
    
    /**
     * Returns a new instance with all dirs except those specified.
     *
     * @param array<int, string> $names
     * @return static
     */
    public function except(array $names): static;
    
    /**
     * Returns a new instance with the dirs sorted.
     *
     * @param null|callable $callback If null, sorts by priority, highest first.
     * @return static
     */    
    public function sort(null|callable $callback = null): static;
    
    /**
     * Returns all dirs.
     *
     * @return array<string, DirInterface>
     */
    public function all(): array;

    /**
     * Returns true if dir exists, otherwise false.
     *
     * @param string $name
     * @return bool
     */
    public function has(string $name): bool;
    
    /**
     * Returns the dir by its name.
     *
     * @param string $name
     * @return DirInterface
     *
     * @throws DirNotFoundException
     */
    public function getDir(string $name): DirInterface;
    
    /**
     * Returns the dir (string) by its name.
     *
     * @param string $name
     * @return string
     *
     * @throws DirNotFoundException
     */
    public function get(string $name): string;
}