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

namespace PhpOffice\PhpWordTests\Reader\Word2007;

use Generator;
use PhpOffice\PhpWord\Element\TextRun;
use PhpOffice\PhpWord\SimpleType\Border;
use PhpOffice\PhpWord\SimpleType\TblWidth;
use PhpOffice\PhpWord\SimpleType\VerticalJc;
use PhpOffice\PhpWord\Style;
use PhpOffice\PhpWord\Style\Table;
use PhpOffice\PhpWord\Style\TablePosition;
use PhpOffice\PhpWordTests\AbstractTestReader;

/**
 * Test class for PhpOffice\PhpWord\Reader\Word2007\Styles.
 */
class StyleTest extends AbstractTestReader
{
    /**
     * Test reading of table layout.
     */
    public function testReadTableLayout(): void
    {
        $documentXml = '<w:tbl>
            <w:tblPr>
                <w:tblLayout w:type="fixed"/>
            </w:tblPr>
        </w:tbl>';

        $phpWord = $this->getDocumentFromString(['document' => $documentXml]);

        $elements = $phpWord->getSection(0)->getElements();
        self::assertInstanceOf('PhpOffice\PhpWord\Element\Table', $elements[0]);
        self::assertInstanceOf('PhpOffice\PhpWord\Style\Table', $elements[0]->getStyle());
        self::assertEquals(Table::LAYOUT_FIXED, $elements[0]->getStyle()->getLayout());
    }

    /**
     * Test reading of table position.
     */
    public function testReadTablePosition(): void
    {
        $documentXml = '<w:tbl>
            <w:tblPr>
                <w:tblpPr w:leftFromText="10" w:rightFromText="20" w:topFromText="30" w:bottomFromText="40" w:vertAnchor="page" w:horzAnchor="margin" w:tblpXSpec="center" w:tblpX="50" w:tblpYSpec="top" w:tblpY="60"/>
            </w:tblPr>
        </w:tbl>';

        $phpWord = $this->getDocumentFromString(['document' => $documentXml]);

        $elements = $phpWord->getSection(0)->getElements();
        self::assertInstanceOf('PhpOffice\PhpWord\Element\Table', $elements[0]);
        self::assertInstanceOf('PhpOffice\PhpWord\Style\Table', $elements[0]->getStyle());
        self::assertNotNull($elements[0]->getStyle()->getPosition());
        self::assertInstanceOf('PhpOffice\PhpWord\Style\TablePosition', $elements[0]->getStyle()->getPosition());
        /** @var TablePosition $tableStyle */
        $tableStyle = $elements[0]->getStyle()->getPosition();
        self::assertEquals(10, $tableStyle->getLeftFromText());
        self::assertEquals(20, $tableStyle->getRightFromText());
        self::assertEquals(30, $tableStyle->getTopFromText());
        self::assertEquals(40, $tableStyle->getBottomFromText());
        self::assertEquals(TablePosition::VANCHOR_PAGE, $tableStyle->getVertAnchor());
        self::assertEquals(TablePosition::HANCHOR_MARGIN, $tableStyle->getHorzAnchor());
        self::assertEquals(TablePosition::XALIGN_CENTER, $tableStyle->getTblpXSpec());
        self::assertEquals(50, $tableStyle->getTblpX());
        self::assertEquals(TablePosition::YALIGN_TOP, $tableStyle->getTblpYSpec());
        self::assertEquals(60, $tableStyle->getTblpY());
    }

    public function testReadTableCellNoWrap(): void
    {
        $documentXml = '<w:tbl>
          <w:tr>
            <w:tc>
              <w:tcPr>
                <w:noWrap />
              </w:tcPr>
            </w:tc>
          </w:tr>
        </w:tbl>';

        $phpWord = $this->getDocumentFromString(['document' => $documentXml]);

        $elements = $phpWord->getSection(0)->getElements();
        self::assertInstanceOf('PhpOffice\PhpWord\Element\Table', $elements[0]);
        $rows = $elements[0]->getRows();
        $cells = $rows[0]->getCells();
        self::assertTrue($cells[0]->getStyle()->getNoWrap());
    }

    /**
     * Test reading of cell spacing.
     */
    public function testReadTableCellSpacing(): void
    {
        $documentXml = '<w:tbl>
            <w:tblPr>
                <w:tblCellSpacing w:w="10.5" w:type="dxa"/>
            </w:tblPr>
        </w:tbl>';

        $phpWord = $this->getDocumentFromString(['document' => $documentXml]);

        $elements = $phpWord->getSection(0)->getElements();
        self::assertInstanceOf('PhpOffice\PhpWord\Element\Table', $elements[0]);
        self::assertInstanceOf('PhpOffice\PhpWord\Style\Table', $elements[0]->getStyle());
        /** @var Table $tableStyle */
        $tableStyle = $elements[0]->getStyle();
        self::assertEquals(TblWidth::AUTO, $tableStyle->getUnit());
        self::assertEquals(10.5, $tableStyle->getCellSpacing());
    }

    public function testReadTableCellStyle(): void
    {
        $documentXml = '<w:tbl>
          <w:tr>
            <w:tc>
              <w:tcPr>
                <w:tcBorders>
                  <w:top w:val="single" w:sz="4" w:space="0" w:color="auto"/>
                  <w:bottom w:val="double" w:sz="4" w:space="0" w:color="auto"/>
                </w:tcBorders>
                <w:tcMar>
                  <w:top w:w="720" w:type="dxa"/>
                  <w:start w:w="720" w:type="dxa"/>
                  <w:bottom w:w="0" w:type="dxa"/>
                  <w:end w:w="720" w:type="dxa"/>
                </w:tcMar>
              </w:tcPr>
            </w:tc>
          </w:tr>
        </w:tbl>';

        $phpWord = $this->getDocumentFromString(['document' => $documentXml]);

        $elements = $phpWord->getSection(0)->getElements();
        self::assertInstanceOf('PhpOffice\PhpWord\Element\Table', $elements[0]);
        $rows = $elements[0]->getRows();
        self::assertInstanceOf('PhpOffice\PhpWord\Element\Row', $rows[0]);
        $cells = $rows[0]->getCells();
        self::assertInstanceOf('PhpOffice\PhpWord\Element\Cell', $cells[0]);
        $styleCell = $cells[0]->getStyle();
        self::assertInstanceOf('PhpOffice\PhpWord\Style\Cell', $styleCell);

        self::assertEquals(4, $styleCell->getBorderTopSize());
        self::assertEquals(Border::SINGLE, $styleCell->getBorderTopStyle());
        self::assertEquals('auto', $styleCell->getBorderTopColor());

        self::assertEquals(4, $styleCell->getBorderBottomSize());
        self::assertEquals(Border::DOUBLE, $styleCell->getBorderBottomStyle());
        self::assertEquals('auto', $styleCell->getBorderBottomColor());
    }

    public function testReadTableCellsWithVerticalMerge(): void
    {
        $documentXml = '<w:tbl>
          <w:tr>
            <w:tc>
              <w:tcPr>
                <w:vMerge w:val="restart" />
              </w:tcPr>
            </w:tc>
          </w:tr>
          <w:tr>
            <w:tc>
              <w:tcPr>
                <w:vMerge />
              </w:tcPr>
            </w:tc>
          </w:tr>
          <w:tr>
            <w:tc />
          </w:tr>
        </w:tbl>';

        $phpWord = $this->getDocumentFromString(['document' => $documentXml]);

        $table = $phpWord->getSection(0)->getElements()[0];
        self::assertInstanceOf('PhpOffice\PhpWord\Element\Table', $table);

        $rows = $table->getRows();
        self::assertCount(3, $rows);
        foreach ($rows as $row) {
            self::assertCount(1, $row->getCells());
        }

        self::assertSame('restart', $rows[0]->getCells()[0]->getStyle()->getVMerge());
        self::assertSame('continue', $rows[1]->getCells()[0]->getStyle()->getVMerge());
        self::assertNull($rows[2]->getCells()[0]->getStyle()->getVMerge());
    }

    /**
     * Test reading of position.
     */
    public function testReadPosition(): void
    {
        $documentXml = '<w:p>
            <w:r>
                <w:rPr>
                    <w:position w:val="15"/>
                </w:rPr>
                <w:t xml:space="preserve">This text is lowered</w:t>
            </w:r>
        </w:p>';

        $phpWord = $this->getDocumentFromString(['document' => $documentXml]);

        $elements = $phpWord->getSection(0)->getElements();
        /** @var TextRun $elements */
        $textRun = $elements[0];
        self::assertInstanceOf('PhpOffice\PhpWord\Element\TextRun', $textRun);
        self::assertInstanceOf('PhpOffice\PhpWord\Element\Text', $textRun->getElement(0));
        self::assertInstanceOf('PhpOffice\PhpWord\Style\Font', $textRun->getElement(0)->getFontStyle());
        /** @var Style\Font $fontStyle */
        $fontStyle = $textRun->getElement(0)->getFontStyle();
        self::assertEquals(15, $fontStyle->getPosition());
    }

    public function testReadIndent(): void
    {
        $documentXml = '<w:tbl>
            <w:tblPr>
                <w:tblInd w:w="2160" w:type="dxa"/>
            </w:tblPr>
        </w:tbl>';

        $phpWord = $this->getDocumentFromString(['document' => $documentXml]);

        $elements = $phpWord->getSection(0)->getElements();
        self::assertInstanceOf('PhpOffice\PhpWord\Element\Table', $elements[0]);
        self::assertInstanceOf('PhpOffice\PhpWord\Style\Table', $elements[0]->getStyle());
        /** @var Table $tableStyle */
        $tableStyle = $elements[0]->getStyle();
        self::assertSame(TblWidth::TWIP, $tableStyle->getIndent()->getType());
        self::assertSame(2160, $tableStyle->getIndent()->getValue());
    }

    public function testReadTableRTL(): void
    {
        $documentXml = '<w:tbl>
            <w:tblPr>
                <w:bidiVisual w:val="1"/>
            </w:tblPr>
        </w:tbl>';

        $phpWord = $this->getDocumentFromString(['document' => $documentXml]);

        $elements = $phpWord->getSection(0)->getElements();
        self::assertInstanceOf('PhpOffice\PhpWord\Element\Table', $elements[0]);
        self::assertInstanceOf('PhpOffice\PhpWord\Style\Table', $elements[0]->getStyle());
        /** @var Table $tableStyle */
        $tableStyle = $elements[0]->getStyle();
        self::assertTrue($tableStyle->isBidiVisual());
    }

    public function testReadHidden(): void
    {
        $documentXml = '<w:p>
            <w:r>
                <w:rPr>
                    <w:vanish/>
                </w:rPr>
                <w:t xml:space="preserve">This text is hidden</w:t>
            </w:r>
        </w:p>';

        $phpWord = $this->getDocumentFromString(['document' => $documentXml]);

        $elements = $phpWord->getSection(0)->getElements();
        /** @var TextRun $elements */
        $textRun = $elements[0];
        self::assertInstanceOf('PhpOffice\PhpWord\Element\TextRun', $textRun);
        self::assertInstanceOf('PhpOffice\PhpWord\Element\Text', $textRun->getElement(0));
        self::assertInstanceOf('PhpOffice\PhpWord\Style\Font', $textRun->getElement(0)->getFontStyle());
        /** @var Style\Font $fontStyle */
        $fontStyle = $textRun->getElement(0)->getFontStyle();
        self::assertTrue($fontStyle->isHidden());
    }

    public function testReadHeading(): void
    {
        Style::resetStyles();

        $documentXml = '<w:style w:type="paragraph" w:styleId="Ttulo1">
            <w:name w:val="heading 1"/>
            <w:basedOn w:val="Normal"/>
            <w:uiPriority w:val="1"/>
            <w:qFormat/>
            <w:pPr>
                <w:outlineLvl w:val="0"/>
            </w:pPr>
            <w:rPr>
                <w:rFonts w:ascii="Times New Roman" w:eastAsia="Times New Roman" w:hAnsi="Times New Roman"/>
                <w:b/>
                <w:bCs/>
            </w:rPr>
        </w:style>';

        $name = 'Heading_1';

        $this->getDocumentFromString(['styles' => $documentXml]);
        self::assertInstanceOf('PhpOffice\\PhpWord\\Style\\Font', Style::getStyle($name));
    }

    public function testPageVerticalAlign(): void
    {
        $documentXml = '<w:sectPr>
            <w:vAlign w:val="center"/>
        </w:sectPr>';

        $phpWord = $this->getDocumentFromString(['document' => $documentXml]);

        $sectionStyle = $phpWord->getSection(0)->getStyle();
        self::assertEquals(VerticalJc::CENTER, $sectionStyle->getVAlign());
    }

    /**
     * @dataProvider providerIndentation
     */
    public function testIndentation(
        string $indent,
        float $left,
        float $right,
        ?float $hanging,
        float $firstLine,
        int $firstLineChars
    ): void {
        $documentXml = "<w:p>
            <w:pPr>
                $indent
            </w:pPr>
            <w:r>
                <w:t>1.</w:t>
            </w:r>
        </w:p>";

        $phpWord = $this->getDocumentFromString(['document' => $documentXml]);

        $section = $phpWord->getSection(0);
        $textRun = $section->getElements()[0];
        self::assertInstanceOf(TextRun::class, $textRun);

        $paragraphStyle = $textRun->getParagraphStyle();
        self::assertInstanceOf(Style\Paragraph::class, $paragraphStyle);

        $indentation = $paragraphStyle->getIndentation();
        self::assertSame($left, $indentation->getLeft());
        self::assertSame($right, $indentation->getRight());
        self::assertSame($hanging, $indentation->getHanging());
        self::assertSame($firstLine, $indentation->getFirstLine());
        self::assertSame($firstLineChars, $indentation->getFirstLineChars());
    }

    /**
     * @return Generator<array{0:string, 1:float, 2:float, 3:null|float, 4: float, 5: int}>
     */
    public static function providerIndentation()
    {
        yield [
            '<w:ind w:left="709" w:right="488" w:hanging="10" w:firstLine="490" w:firstLineChars="140"/>',
            709.00,
            488.00,
            10.0,
            490.00,
            140,
        ];
        yield [
            '<w:ind w:left="709" w:right="488" w:hanging="10" w:firstLine="490"/>',
            709.00,
            488.00,
            10.0,
            490.00,
            0,
        ];
        yield [
            '<w:ind w:hanging="10" w:firstLine="490"/>',
            0,
            0,
            10.0,
            490.00,
            0,
        ];
        yield [
            '<w:ind w:left="709"/>',
            709.00,
            0,
            0,
            0,
            0,
        ];
        yield [
            '<w:ind w:right="488"/>',
            0,
            488.00,
            0,
            0,
            0,
        ];
    }
}
