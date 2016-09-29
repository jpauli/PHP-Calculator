<?php
/**
 * Calculator ArrayAccess decorated memory handler
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

/**
 * ArrayAccess decorator memory class
 *
 * @package Calculator
 * @subpackage Memory
 * @author Julien Pauli <jpauli@php.net>
 * @copyright 2016 Julien Pauli <jpauli@php.net>
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 * @version Release: @package_version@
 */
class ArrayAccessDecorator implements MemoryInterface, \ArrayAccess
{
    public function __construct(private MemoryInterface $memory)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function load(MemorySlots $memoryNum) : float
    {
        return $this->memory->load($memoryNum);
    }

    /**
     * {@inheritDoc}
     */
    public function save(MemorySlots $memoryNum, float $value) : void
    {
        $this->memory->save($memoryNum, $value);
    }

    /**
     * {@inheritDoc}
     */
    public function offsetGet($offset)
    {
        return $this->memory->load($offset);
    }

    /**
     * {@inheritDoc}
     */
    public function offsetSet($offset, $value)
    {
        return $this->memory->save($offset, $value);
    }

    /**
     * {@inheritDoc}
     */
    public function offsetExists($offset)
    {
        try {
            return $this->memory->load($offset);
        } catch (MemoryException) {
            return false;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function offsetUnset($offset)
    {
        return $this->memory->clear($offset);
    }

    /**
     * {@inheritDoc}
     */
    public function clear(MemorySlots $memoryNum) : void
    {
        $this->memory->clear($memoryNum);
    }
}