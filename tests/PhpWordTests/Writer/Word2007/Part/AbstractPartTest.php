<?php

/**
 * This file is part of PHPWord - A pure PHP library for reading and writing
 * word processing documents.
 *
 * PHPWord is free software distributed under the terms of the GNU Lesser
 * General Public License version 3 as published by the Free Software Foundation.
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code. For the full list of
 * contributors, visit https://github.com/PHPOffice/PHPWord/contributors.
 *
 * @see         https://github.com/PHPOffice/PHPWord
 *
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWordTests\Writer\Word2007\Part;

use Exception;
use PhpOffice\PhpWord\Writer\Word2007;

/**
 * @runTestsInSeparateProcesses
 */
class AbstractPartTest extends \PHPUnit\Framework\TestCase
{
    /**
     * covers   ::setParentWriter
     * covers   ::getParentWriter.
     */
    public function testSetGetParentWriter(): void
    {
        // @phpstan-ignore-next-line
        if (method_exists($this, 'getMockForAbstractClass')) {
            $stub = $this->getMockForAbstractClass(Word2007\Part\AbstractPart::class);
        } else {
            /** @var Word2007\Part\AbstractPart $stub */
            $stub = new class() extends Word2007\Part\AbstractPart {
                public function write(): string
                {
                    return '';
                }
            };
        }
        $stub->setParentWriter(new Word2007());
        self::assertEquals(new Word2007(), $stub->getParentWriter());
    }

    /**
     * covers   ::getParentWriter.
     */
    public function testSetGetParentWriterNull(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('No parent WriterInterface assigned.');
        // @phpstan-ignore-next-line
        if (method_exists($this, 'getMockForAbstractClass')) {
            $stub = $this->getMockForAbstractClass(Word2007\Part\AbstractPart::class);
        } else {
            /** @var Word2007\Part\AbstractPart $stub */
            $stub = new class() extends Word2007\Part\AbstractPart {
                public function write(): string
                {
                    return '';
                }
            };
        }
        $stub->getParentWriter();
    }
}
