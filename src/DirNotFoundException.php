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

use Exception;
use Throwable;

/**
 * DirNotFoundException
 */
class DirNotFoundException extends Exception
{
    /**
     * Create a new DirNotFoundException
     *
     * @param string $name The dir name.
     * @param string $message The message
     * @param int $code
     * @param null|Throwable $previous
     */
    public function __construct(
        protected string $name,
        string $message = '',
        int $code = 0,
        null|Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
    
    /**
     * Get the dir name.
     *
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }    
}