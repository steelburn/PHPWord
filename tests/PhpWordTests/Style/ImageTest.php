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

namespace PhpOffice\PhpWordTests\Style;

use InvalidArgumentException;
use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWord\Style\Image;

/**
 * Test class for PhpOffice\PhpWord\Style\Image.
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Style\Image
 *
 * @runTestsInSeparateProcesses
 */
class ImageTest extends \PHPUnit\Framework\TestCase
{
    public function testSetGetNormalInt(): void
    {
        $object = new Image();
        foreach ([
            'width' => 200,
            'height' => 200,
            'marginTop' => 240,
            'marginLeft' => 240,
            'wrapDistanceLeft' => 10,
            'wrapDistanceRight' => 20,
            'wrapDistanceTop' => 30,
            'wrapDistanceBottom' => 40,
        ] as $key => $value) {
            $set = "set{$key}";
            $get = "get{$key}";
            $object->$set($value);
            self::assertEquals($value, $object->$get());
        }
    }

    public function testSetGetNormalString(): void
    {
        $object = new Image();
        foreach ([
            'alignment' => Jc::START,
            'wrappingStyle' => 'inline',
        ] as $key => $value) {
            $set = "set{$key}";
            $get = "get{$key}";
            $object->$set($value);
            self::assertEquals($value, $object->$get());
        }
    }

    /**
     * Test setStyleValue method.
     */
    public function testSetStyleValue(): void
    {
        $object = new Image();

        $properties = [
            'width' => 200,
            'height' => 200,
            'alignment' => Jc::START,
            'marginTop' => 240,
            'marginLeft' => 240,
            'position' => 10,
            'positioning' => Image::POSITION_ABSOLUTE,
            'posHorizontal' => Image::POSITION_HORIZONTAL_CENTER,
            'posVertical' => Image::POSITION_VERTICAL_TOP,
            'posHorizontalRel' => Image::POSITION_RELATIVE_TO_COLUMN,
            'posVerticalRel' => Image::POSITION_RELATIVE_TO_IMARGIN,
            'wrapDistanceLeft' => 10,
            'wrapDistanceRight' => 20,
            'wrapDistanceTop' => 30,
            'wrapDistanceBottom' => 40,
        ];
        foreach ($properties as $key => $value) {
            $get = "get{$key}";
            $object->setStyleValue("{$key}", $value);
            self::assertEquals($value, $object->$get());
        }
    }

    /**
     * Test setWrappingStyle exception.
     */
    public function testSetWrappingStyleException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $object = new Image();
        $object->setWrappingStyle('foo');
    }
}
