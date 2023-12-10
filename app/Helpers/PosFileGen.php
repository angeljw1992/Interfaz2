<?php

namespace Flores;

/**
 * Generate spreadsheet file
 *
 * @author Nelson Flores <nelson.flores@live.com>
 */
class PosFileGen
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
     * base_path storage/
     */
    public static $DEFAULT_PATH = "storage";

    public function __construct($columns = [])
    {
        PosFileGen::$DELIMITER_COMMA;
        PosFileGen::$DEFAULT_PATH = base_path(PosFileGen::$DEFAULT_PATH);


        $this->DEFAULT_NAME = time().".data";
        $this->delimiter = PosFileGen::$DELIMITER_COMMA;
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
            $output.="\r\n";
        }


        foreach ($this->datas as $i => $value) {
            $output.=implode($delimiter, $value);
            $output.="\r\n";
        }

        $this->output = $output;

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
    private function str_to_utf8($text)
    {
        return iconv(mb_detect_encoding($text, mb_detect_order(), true), "UTF-8", $text);
    }


    /**
     * $type = PosFileGen::$HASH_MD5
     */

    public function hash($str, bool $binary = false, $type = "md5")
    {
        $type = ($type == null) ? "md5" : $type;
        
        $param = $this->str_to_utf8($str);
        $paramArrayOfbyte = unpack('C*', $param);
        
        $messageDigest = hash_init("MD5");;
    
        hash_update($messageDigest, implode(array_map("chr", $paramArrayOfbyte)));
        
        
        $hash = hash_final($messageDigest,true);
       
        $hash = unpack("c*", $hash); 
        $hash = array_values($hash);

        $hash =  $this->getHex($hash);


        return $hash;
    }

    public function generatePassword($param1, $param2)
    {
        $param1 = $this->str_to_utf8($param1);
        $param2 = $this->str_to_utf8($param2);

        $paramArrayOfbyte1 = unpack('C*', $param1);
        $paramArrayOfbyte2 =  unpack('C*', $param2);

        $paramArrayOfbyte1 = array_values($paramArrayOfbyte1);
        $paramArrayOfbyte2 = array_values($paramArrayOfbyte2);


        $arrayOfByte1 = array_fill(0, 64, 0);
        $arrayOfByte2 = array_fill(0, 64, 0);

        if (count($paramArrayOfbyte1) === 0 || count($paramArrayOfbyte1) > 64) {
            throw new \Exception("User Id should have length between 1 and 64 bytes");
        }

        if (count($paramArrayOfbyte2) < 1 || count($paramArrayOfbyte2) > 16) {
            throw new \Exception("User password should have length between 1 and 16 bytes");
        }


        foreach ($paramArrayOfbyte2 as $b => $value) {
            $arrayOfByte1[$b] = $paramArrayOfbyte2[$b];
            $arrayOfByte2[$b] = $paramArrayOfbyte2[$b];
        }

        for ($b = 0; $b < 64; $b++) {
            $arrayOfByte1[$b] = ($arrayOfByte1[$b] ^ 0x36);
            $arrayOfByte2[$b] = ($arrayOfByte2[$b] ^ 0x5C);
        }



        $str = null;
        try {

            $messageDigest = hash_init("MD5");
            
            hash_update($messageDigest, implode(array_map("chr", $arrayOfByte1)));
            hash_update($messageDigest, implode(array_map("chr", $paramArrayOfbyte1)));
            
            $hash = hash_final($messageDigest,true); 

            $arrayOfByte = unpack("c*", $hash); 
            $arrayOfByte = array_values($arrayOfByte);
      

            
            $messageDigest = hash_init("MD5");;
            
            hash_update($messageDigest, implode(array_map("chr", $arrayOfByte2)));
            hash_update($messageDigest, implode(array_map("chr", $arrayOfByte)));
            
            $hash = hash_final($messageDigest,true);
           
            $hash = unpack("c*", $hash); 
            $hash = array_values($hash);

            $str =  $this->getHex($hash);
        } catch (\Exception $e) {
            throw $e;
        }
        return $str;
    } 

    private function getHex($arr){

        $string = "";
        foreach ($arr as $value) {
            $string .= dechex(0xFF & $value);
        }

        return $string;

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
            if (!isset($line->{$value})) {
                $line->{$value} = "";
            }
        }

        foreach ($line as $i => $value) {
            if (in_array($i, $this->columns)) {
                $newline->{$i} = $value;
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
     * $delimiter = PosFileGen::$DELIMITER_COMMA
     */
    public function setDelimiter($delimiter = null)
    {
        $this->delimiter = ($delimiter == null) ? PosFileGen::$DELIMITER_COMMA : $delimiter;
        return $this;
    }


    /**
     * $delimiter = PosFileGen::$DEFAULT_PATH
     * $name = PosFileGen::$DEFAULT_NAME
     */
    public function save($name = null, $path = null)
    {
        $path = ($path==null) ? PosFileGen::$DEFAULT_PATH : $path;
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
