<?php

namespace PHPSimpleExcel;

use PHPSimpleExcel\Element\Row;
use PHPSimpleExcel\Element\ElementInterface;

/**
 * Generating simple excel documents in PHP. Made on the basis of php-excel from Oliver Schwarz
 *
 * @see https://github.com/oliverschwarz/php-excel
 *
 * @author Patryk Szlagowski [FunkyMonkeyLabs https://github.com/funkymonkeylabs] <szlagowskipatryk@gmail.com>
 */
class PHPSimpleExcel
{
    /**
     * Header (of document)
     * @var string
     */
    private $header = "<?xml version=\"1.0\" encoding=\"%s\"?\>
    <Workbook
        xmlns=\"urn:schemas-microsoft-com:office:spreadsheet\"
        xmlns:x=\"urn:schemas-microsoft-com:office:excel\"
        xmlns:ss=\"urn:schemas-microsoft-com:office:spreadsheet\"
        xmlns:html=\"http://www.w3.org/TR/REC-html40\"
    >";

    /**
     * Footer (of document)
     * @var string
     */
    private $footer = "</Workbook>";

    /**
     * Lines to output in the excel document
     * @var array
     */
    private $lines = array();

    /**
     * Used encoding
     * @var string
     */
    private $encoding;

    /**
     * Convert variable types
     * @var boolean
     */
    private $convertTypes;

    /**
     * Worksheet title
     * @var string
     */
    private $worksheetTitle;

    /**
     * array of named styles
     *
     * @var array
     */
    private $style = array();

    /**
     * @param string $encoding Encoding to be used (defaults to UTF-8)
     * @param boolean $convertTypes Convert variables to field specification
     * @param string $worksheetTitle Title for the worksheet
     */
    public function __construct($encoding = 'UTF-8', $convertTypes = false, $worksheetTitle = 'Table1')
    {
        $this->convertTypes = $convertTypes;
        $this->setEncoding($encoding);
        $this->setWorksheetTitle($worksheetTitle);
    }

    /**
     * Set encoding
     *
     * @param string
     */
    public function setEncoding($encoding)
    {
        $this->encoding = $encoding;
    }

    /**
     * Set worksheet title
     *
     * @param string $title
     */
    public function setWorksheetTitle($title)
    {
        $title = preg_replace("/[\\\|:|\/|\?|\*|\[|\]]/", "", $title);
        $title = substr($title, 0, 31);
        $this->worksheetTitle = $title;
    }

    /**
     * add named style for using it for cell
     *
     * @param string $name
     * @param array $style
     */
    public function addNamedStyle($name, array $style = array())
    {
        if (!empty($this->style[$name])) {
            throw new \InvalidArgumentException("Style " . $name . " already exists!");
        }
        $this->style[$name] = $style;
    }

    /**
     * Add row
     *
     * @param array $array
     */
    public function addRow(array $array = array())
    {
        $row = new Row($this->encoding, $this->convertTypes);

        foreach ($array as $key => $value) {
            $row->addCell($value);
        }
        $this->lines[] = $row->end();
    }

    /**
     * Add an array to the document
     *
     * @param array 2-dimensional array
     */
    public function addArray($array)
    {
        foreach ($array as $k => $v) {
            $this->addRow($v);
        }
    }

    /**
     * Start Row (returning Row object)
     *
     * @return Row
     */
    public function startRow()
    {
        $uniqueId = md5(microtime(true) . rand(0, 999));
        $this->lines[$uniqueId] = new Row($this->encoding, $this->convertTypes);
        return $this->lines[$uniqueId];
    }


    /**
     * Generate the excel file
     * Fixed version - send correct attachment file in IE8 with ssl
     *
     * @param string $filename Name of excel file to generate (...xls)
     * @param string $contentDisposition (inline|attachment)
     */
    public function generateXML($filename = 'excel-export', $contentDisposition = 'inline')
    {
        // correct/validate filename
        $filename = preg_replace('/[^aA-zZ0-9\_\-]/', '', $filename);

        // deliver header (as recommended in php manual)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: ' . $contentDisposition . ';filename="' . $filename . '.xls"');
        header("Cache-Control: private");
        header("Pragma: private");
        header("Content-Transfer-Encoding: binary\n");

        // print out document to the browser
        // need to use stripslashes for the damn ">"
        echo stripslashes(sprintf($this->header, $this->encoding));
        echo $this->prepareStyles();
        echo "\n<Worksheet ss:Name=\"" . $this->worksheetTitle . "\">\n";
        echo "<Table>";
        foreach ($this->lines as $line) {
            if ($line instanceof ElementInterface) {
                echo $line->render();
            } else {
                echo $line;
            }
        }


        echo "</Table>\n</Worksheet>\n";
        echo $this->footer;
    }

    /**
     * @return string
     */
    private function prepareStyles()
    {
        $completeStyle = array();
        foreach ($this->style as $name => $style) {
            $styles = array();
            foreach ($style as $singleName => $singleStyle) {
                $tmpStyle = '';
                foreach ($singleStyle as $key => $value) {
                    $tmpStyle = sprintf("%s=\"%s\"", $key, $value);
                }
                $styles[] = sprintf("<%s %s />", $singleName, $tmpStyle);
            }

            $completeStyle[] = sprintf("<Style ss:ID=\"%s\">%s</Style>", $name, implode("\n", $styles));
        }

        return sprintf("<Styles>%s</Styles>", implode("\n", $completeStyle));
    }
}