<?php

namespace Flores;

/**
 * Generate spreadsheet file
 *
 * @author Nelson Flores <nelson.flores@live.com>
 */
class OutputGen
{
    private $debug = false;
    private $show_header = false;

    private $columns = [];
    private $datas = [];

    private $filename = null;
    private $output = null;
    private $delimiter;


    public static $DELIMITER_COMMA = ",";
    public static $DELIMITER_TAB = "\t";
    /**
     * DEFAULT_NAME = timestamp
     */
    private $DEFAULT_NAME = "";

    /**
     * storage/
     */
    public static $DEFAULT_PATH = "";

    public static $HASH_MD5 = "md5";
    public static $HASH_sha1 = "sha1";
    public static $HASH_sha256 = "sha256";

    public function __construct($columns = [])
    {
        OutputGen::$DELIMITER_COMMA;
        OutputGen::$DEFAULT_PATH = storage_path(OutputGen::$DEFAULT_PATH);


        $this->DEFAULT_NAME = time().".data";
        $this->delimiter = OutputGen::$DELIMITER_COMMA;
        $this->addColumns($columns);



    }

    private function getOutput()
    {
        return $this->output;
    }

    private function generate()
    {

        $output = "";

        $delimiter = $this->delimiter;

        if ($this->show_header) {
            $output = implode($delimiter, $this->columns);
            $output.="\n";
        }

      
        foreach ($this->datas as $i => $value) {
            $output.=implode($delimiter, $value);
            $output.="\n";
        }

        $this->output = $output."\n";

        return $this;
    }


    public function addColumn($name, $duplicate = true)
    {
        if (in_array($name, $this->columns) and $duplicate == false) {

            if ($this->debug == true) {
                throw new \Exception("Column already exists: ".$name, 400);
            }

            return $this;
        }

        array_push($this->columns, $name);

        return $this;
    }

    public function addColumns($names = [], $duplicate = true)
    {
        foreach ($names as $name) {
            $this->addColumn($name, $duplicate);
        }

        return $this;
    }


    public function generatePassword($str)
    {
        return $this->hash($str);
    }

    private function generateMD5($str)
    {
        return hash("md5", $str);
    }
    private function generateSHA1($str)
    {
        return sha1($str);
    }
    private function generateSHA256($str)
    {
        return hash("sha256", $str);
    }

    /**
     * $type = OutputGen::$HASH_MD5
     */   public function hash($str, $type = null)
    {
        $type = ($type == null) ? OutputGen::$HASH_MD5 : $type;

        $hash = "";

        switch ($type) {
            case 'md5':
                $hash = $this->generateMD5($str);
                break;
            case 'sha1':
                $hash = $this->generateSHA1($str);
                break;
            case 'sha256':
                $hash = $this->generateSHA256($str);
                break;
            default:
                # code...
                break;
        }

        return $hash;
    }


    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * Create an object with all columns as attributes
     */
    public function newLine()
    {
        $object = new \stdClass();

        foreach ($this->columns as $i => $value) {
            $object->{$value} = "";
        }

        return $object;
    }

    /**
     * Add line to table
     */
    public function add(\stdClass $line)
    {
        $newline = $this->newLine();
        foreach ($this->columns as $i => $value) {
            if (!isset($line->{$i})) {
                $line->{$i} = "";
            }
        }

        foreach ($line as $i => $value) {
            if (in_array($i, $this->columns)) {
                $newline->{$i} = $value;
            } else {
                if ($this->debug == true) {
                    throw new \Exception("Invalid Column: ".$i, 400);
                }
            }
        }

        $arr = json_decode(json_encode($newline), true);
        array_push($this->datas, $arr);
        return $this;
    }


    public function debug($bool = false)
    {
        $this->debug = $bool;
        return $this;
    }
    public function showHeader($bool = true)
    {
        $this->show_header = $bool;
        return $this;
    }

    /**
     * $delimiter = OutputGen::$DELIMITER_COMMA
     */
    public function setDelimiter($delimiter = null)
    {
        $this->delimiter = ($delimiter == null) ? OutputGen::$DELIMITER_COMMA : $delimiter;
        return $this;
    }


    /**
     * $delimiter = OutputGen::$DEFAULT_PATH
     * $name = OutputGen::$DEFAULT_NAME
     */
    public function save($name = null, $path = null)
    {
        $path = ($path==null) ? OutputGen::$DEFAULT_PATH : $path;
        $name = ($name==null) ? $this->DEFAULT_NAME : $name;


        $path = trim($path);
        $name = trim($name);

        $path = (str_ends_with($path, "/")==true) ? $path : $path."/";

        $filename = $path.$name;

        try {
            file_put_contents($filename, $this->toString());
        } catch (\Exception $e) {
            if ($this->debug == true) {
                throw $e;
            }
        }

        $this->filename = $filename;
        return $this;
    }
    public function getDefaultName()
    {
        return $this->DEFAULT_NAME;
    }

    public function toString()
    {
        return $this->generate()->getOutput();
    }

}
