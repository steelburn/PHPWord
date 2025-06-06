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

namespace PhpOffice\PhpWordTests\Element;

use DateTime;
use InvalidArgumentException;
use PhpOffice\PhpWord\Element\Comment;
use PhpOffice\PhpWord\Element\Section;
use PhpOffice\PhpWord\Element\Text;

/**
 * Test class for PhpOffice\PhpWord\Element\Header.
 *
 * @runTestsInSeparateProcesses
 */
class CommentTest extends \PHPUnit\Framework\TestCase
{
    /**
     * New instance.
     */
    public function testConstructDefault(): void
    {
        $author = 'Test User';
        $date = new DateTime('2000-01-01');
        $initials = 'default_user';
        $oComment = new Comment($author, $date, $initials);

        $oText = new Text('dummy text');
        $oComment->setStartElement($oText);
        $oComment->setEndElement($oText);

        self::assertEquals($author, $oComment->getAuthor());
        self::assertEquals($date, $oComment->getDate());
        self::assertEquals($initials, $oComment->getInitials());
        self::assertEquals($oText, $oComment->getStartElement());
        self::assertEquals($oText, $oComment->getEndElement());
    }

    /**
     * Two comments on same text.
     */
    public function testTwoCommentsOnSameText(): void
    {
        $section = new Section(0);
        $text = $section->addText('Text');

        $comment1 = new Comment('Author1', new DateTime(), 'A1');
        $comment1->addText('Comment1');

        $comment2 = new Comment('Author2', new DateTime(), 'A2');
        $comment2->addText('Comment2');

        $comment1->setStartElement($text);
        $comment2->setStartElement($text);

        $text->setCommentRangeStart($comment1);
        $text->setCommentRangeEnd($comment1);

        $text->setCommentRangeStart($comment2);
        $text->setCommentRangeEnd($comment2);

        self::assertEquals(2, $text->getCommentsRangeStart()->countItems());
        self::assertEquals(2, $text->getCommentsRangeEnd()->countItems());

        self::assertEquals($text->getCommentsRangeStart()->getItem(0)->getElementId(), $comment1->getElementId());
        self::assertEquals($text->getCommentsRangeEnd()->getItem(0)->getElementId(), $comment1->getElementId());

        self::assertEquals($text->getCommentsRangeStart()->getItem(1)->getElementId(), $comment2->getElementId());
        self::assertEquals($text->getCommentsRangeEnd()->getItem(1)->getElementId(), $comment2->getElementId());
    }

    /**
     * Add text.
     */
    public function testAddText(): void
    {
        $oComment = new Comment('Test User', new DateTime(), 'my_initials');
        $element = $oComment->addText('text');

        self::assertInstanceOf('PhpOffice\\PhpWord\\Element\\Text', $element);
        self::assertCount(1, $oComment->getElements());
        self::assertEquals('text', $element->getText());
    }

    /**
     * Get elements.
     */
    public function testGetElements(): void
    {
        $oComment = new Comment('Test User', new DateTime(), 'my_initials');

        self::assertIsArray($oComment->getElements());
    }

    /**
     * Set/get relation Id.
     */
    public function testRelationId(): void
    {
        $oComment = new Comment('Test User', new DateTime(), 'my_initials');

        $iVal = mt_rand(1, 1000);
        $oComment->setRelationId($iVal);
        self::assertEquals($iVal, $oComment->getRelationId());
    }

    public function testExceptionOnCommentStartOnComment(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $dummyComment = new Comment('Test User', new DateTime(), 'my_initials');
        $oComment = new Comment('Test User', new DateTime(), 'my_initials');
        $oComment->setCommentRangeStart($dummyComment);
    }

    public function testExceptionOnCommentEndOnComment(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $dummyComment = new Comment('Test User', new DateTime(), 'my_initials');
        $oComment = new Comment('Test User', new DateTime(), 'my_initials');
        $oComment->setCommentRangeEnd($dummyComment);
    }
}
