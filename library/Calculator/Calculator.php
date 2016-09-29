<?php
/**
 * Calculator
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
 * @author Julien Pauli <jpauli@php.net>
 * @copyright 2016 Julien Pauli <jpauli@php.net>
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 */
namespace Calculator;

use Calculator\Memory\MemoryInterface;
use Calculator\Memory\Exception\NoResultException;

/**
 * Calculator class
 *
 * @package Calculator
 * @author Julien Pauli <jpauli@php.net>
 * @copyright 2016 Julien Pauli <jpauli@php.net>
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 * @version Release: @package_version@
 */
class Calculator
{
    /**
     * The result of the current
     * operation
     *
     * @var int/float
     */
    private $result;

    /**
     * Memory implementation
     *
     * @var MemoryInterface
     */
    private $memoryStorage;

    /**
     * @param MemoryInterface $m
     */
    public function __construct(MemoryInterface $m)
    {
        $this->setMemory($m);
    }

    /**
     * @param MemoryInterface $m
     * @return Calculator
     */
    public function setMemory(MemoryInterface $m) : self
    {
        $this->memoryStorage = $m;

        return $this;
    }

    /**
     * @return MemoryInterface
     */
    public function getMemory() : MemoryInterface
    {
        return $this->memoryStorage;
    }

    /**
     * Load result stored in memory slot 1
     *
     * @return int/float
     */
    public function loadM1() : float
    {
        return $this->loadMemory(MemoryInterface::MEMORY_SLOT_ONE);
    }

    /**
     * Load result stored in memory slot 2
     *
     * @return int/float
     */
    public function loadM2() : float
    {
        return $this->loadMemory(MemoryInterface::MEMORY_SLOT_TWO);
    }

    /**
     * Load result stored in memory
     *
     * @param int $slot
     * @return int/float
     */
    private function loadMemory(int $slot) : int
    {
        try {
            return $this->memoryStorage->load($slot);
        } catch (NoResultException $e) { }

        return 0;
    }

    /**
     * Save current result to a memory slot
     *
     * @param int $slot
     * @throws \RuntimeException
     * @return Calculator
     */
    private function saveMemory(int $slot) : self
    {
        if ($this->result === null) {
            throw new \RuntimeException("Can't store to memory: no result found");
        }
        $this->memoryStorage->save($slot, $this->result);

        return $this;
    }

    /**
     * Save current result to memory slot 1
     *
     * @return Calculator
     */
    public function saveM1() : self
    {
        return $this->saveMemory(MemoryInterface::MEMORY_SLOT_ONE);
    }

    /**
     * Save current result to memory slot 2
     *
     * @return Calculator
     */
    public function saveM2() : self
    {
        return $this->saveMemory(MemoryInterface::MEMORY_SLOT_TWO);
    }

    /**
     * Performs a math mult
     *
     * @var $a int/float
     * @var $b int/float
     * @return int/float
     */
    public function mult(float $a, float $b) : float
    {
        return $this->result = $a * $b;
    }

    /**
     * Performs a math sub
     *
     * @var $a int/float
     * @var $b int/float
     * @return int/float
     */
    public function sub(float $a, float $b) : float
    {
        return $this->result = $a - $b;
    }

    /**
     * Performs a math add
     *
     * @var $a int/float
     * @var $b int/float
     * @return int/float
     */
    public function add(float $a, float $b) : float
    {
        return $this->result = $a + $b;
    }

    /**
     * Performs a math div
     *
     * @var $a int/float
     * @var $b int/float
     * @return int/float
     */
    public function div(float $a , float $b) : float
    {
        if ($b == 0) {
            return $this->result = INF;
        }

        return $this->result = $a / $b;
    }
}