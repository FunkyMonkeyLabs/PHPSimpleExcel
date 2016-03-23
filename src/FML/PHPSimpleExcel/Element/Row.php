<?php

namespace FML\PHPSimpleExcel\Element;

/**
 * Simple class to menage current row
 *
 * @author Patryk Szlagowski [FunkyMonkeyLabs] <szlagowskipatryk@gmail.com>
 */
class Row implements ElementInterface
{
    /**
     * @var string
     */
    private $data;

    /**
     * @var string
     */
    private $encoding;

    /**
     * @var boolean
     */
    private $guessType;

    /**
     * @param string $encoding
     * @param bool $guessType
     */
    public function __construct($encoding, $guessType = false)
    {
        $this->encoding = $encoding;
        $this->guessType = $guessType;
        $this->data = "<Row>";
    }

    /**
     * Adding cell to row with value, type and name of style
     *
     * @param string $value
     * @param string $type (String|Numeric)
     * @param string $styleName
     */
    public function addCell($value, $type = null, $styleName = null)
    {
        if (!$type) {
            $type = $this->guessType($value);
        }
        $value = htmlentities($value, ENT_COMPAT, $this->encoding);
        $this->data .= "<Cell" . ($styleName ? " ss:StyleID=\"" . $styleName . "\"" : "") . "><Data ss:Type=\"" . $type . "\">" . $value . "</Data></Cell>";
        return $this;
    }

    /**
     * Guessing type for cell
     *
     * @param mixed $value
     * @return string
     */
    private function guessType($value)
    {
        if (!$this->guessType) {
            return "String";
        }
        switch (true) {
            case is_numeric($value):
                return 'Number';
                break;
            default:
                return "String";
                break;
        }
    }

    /**
     * @return string
     */
    public function end()
    {
        $this->data .= "</Row>";
        return $this;
    }

    /**
     * render current row
     *
     * @return string
     */
    public function render()
    {
        return $this->data;
    }
}