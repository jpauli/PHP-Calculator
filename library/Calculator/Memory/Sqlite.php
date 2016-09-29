<?php
/**
 * Calculator Sqlite memory
 *
 * Copyright (c) 2016, Julien Pauli <jpauli@php.net>.
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 * * Redistributions of source code must retain the above copyright
 * notice, this list of conditions and the following disclaimer.
 *
 * * Redistributions in binary form must reproduce the above copyright
 * notice, this list of conditions and the following disclaimer in
 * the documentation and/or other materials provided with the
 * distribution.
 *
 * * Neither the name of Julien Pauli nor the names of his
 * contributors may be used to endorse or promote products derived
 * from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @package Calculator
 * @subpackage Memory
 * @author Julien Pauli <jpauli@php.net>
 * @copyright 2016 Julien Pauli <jpauli@php.net>
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 */
namespace Calculator\Memory;

use Calculator\Memory\Exception\NoResultException;
use Calculator\Memory\Exception\MemoryException;

/**
 * Sqlite memory class
 *
 * @package Calculator
 * @subpackage Memory
 * @author Julien Pauli <jpauli@php.net>
 * @copyright 2016 Julien Pauli <jpauli@php.net>
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 * @version Release: @package_version@
 */
class Sqlite implements MemoryInterface
{
    /**
     * Database
     */
    public readonly \PDO $memory;

    /**
     * @throws MemoryException
     */
    public function __construct(string $SqliteFile)
    {
        try {
            $this->memory = new \PDO("sqlite:$SqliteFile", options:[\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]);
        } catch (\PDOException $e) {
            throw new MemoryException("Can't open database file", previous:$e);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function load(MemorySlots $memoryNum) : float
    {
        try {
            $return = $this->memory->query("SELECT result FROM memory WHERE mem_no={$memoryNum->value}")->fetchColumn(0);
        } catch (\PDOException $e) {
            throw new NoResultException("Could not fetch memory result", $e);
        }
        if ($return === false) {
            throw new NoResultException("No result in memory {$memoryNum->value}");
        }

        return $return;
    }

    /**
     * {@inheritDoc}
     */
    public function save(MemorySlots $memoryNum, float $value) : void
    {
        $this->memory->query("INSERT OR REPLACE INTO memory (mem_no, result) VALUES ($memoryNum->value, $value)");
    }

    /**
     * {@inheritDoc}
     */
    public function clear(MemorySlots $memoryNum) : void
    {
        $this->memory->exec("DELETE FROM memory WHERE mem_no={$memoryNum->value}");
    }
}