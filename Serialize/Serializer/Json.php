<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace VladFilimon\M2PrintService\Serialize\Serializer;

use Magento\Framework\Serialize\Serializer\Json as MagentoJson;
/**
 * Encodes and decodes JSON and checks for errors on these operations
 */
class Json extends MagentoJson
{
    /**
     * @inheritDoc
     * @since 101.0.0
     */
    public function serialize($data)
    {
        //die("Serialize");
        $result = json_encode($data);
        if (false === $result) {
            die('false');
            throw new \InvalidArgumentException("Unable to serialize value. Error: " . json_last_error_msg());
        }
        return $result;
    }


    public function unserialize($string)
    {
       // return parent::unserialize($string);
        //die("unSerialize");
        if($this->is_serialized($string))
        {
            $string = $this->serialize($string);
        }
       // $result = json_decode( rtrim($string, "\0"), true);
        $result = json_decode( $string, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            //var_dump($string);die("END");
            //$result=[];
            //var_dump(rtrim($string, "\0"));
            //die(json_last_error_msg());
            throw new \InvalidArgumentException('Unable to unserialize value.');
        }
        return $result;
    }

    function is_serialized($value, &$result = null)
    {
        // Bit of a give away this one
        if (!is_string($value))
        {
            return false;
        }
        // Serialized false, return true. unserialize() returns false on an
        // invalid string or it could return false if the string is serialized
        // false, eliminate that possibility.
        if ($value === 'b:0;')
        {
            $result = false;
            return true;
        }
        $length = strlen($value);
        $end    = '';
        switch ($value[0])
        {
            case 's':
                if ($value[$length - 2] !== '"')
                {
                    return false;
                }
            case 'b':
            case 'i':
            case 'd':
                // This looks odd but it is quicker than isset()ing
                $end .= ';';
            case 'a':
            case 'O':
                $end .= '}';
                if ($value[1] !== ':')
                {
                    return false;
                }
                switch ($value[2])
                {
                    case 0:
                    case 1:
                    case 2:
                    case 3:
                    case 4:
                    case 5:
                    case 6:
                    case 7:
                    case 8:
                    case 9:
                        break;
                    default:
                        return false;
                }
            case 'N':
                $end .= ';';
                if ($value[$length - 1] !== $end[0])
                {
                    return false;
                }
                break;
            default:
                return false;
        }
        if (($result = @unserialize($value)) === false)
        {
            $result = null;
            return false;
        }
        return true;
    }
}
