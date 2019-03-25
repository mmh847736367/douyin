<?php

namespace Libraries\Utils;


use function GuzzleHttp\Psr7\str;

class Utils
{
    public static $key = 'ZGFQWIUAEDVNSPLHYRMOTBXCKJsjyfuekrdizqwchgalnbtmopxv0495867321/';

    public static function getHex($a)
    {
        $hex_1 = strrev(substr($a,0,4));
        $str_1 = substr($a,4);

        return [$str_1,$hex_1];
        
    }

    public static function getDec($a)
    {
        $b = hexdec($a);
        $c = str_split(substr($b,0,2));
        $d = str_split(substr($b,2));

        return [$c, $d];
    }

    public static function substr($str, $arr)
    {
        $sub = substr($str,$arr[0],$arr[1]);
        return str_replace($sub, "", $str);
    }

    public static function getPos($a, $b)
    {
        $b[0] = strlen($a) - $b[0] - $b[1];

        return $b;
    }

    public static function deSecret($secret)
    {
        list($str1,$hex1) = self::getHex($secret);
        list($pre, $tail) = self::getDec($hex1);
        $d = self::substr($str1, $pre);
        $kk = self::substr($d, self::getPos($d,$tail));
        return base64_decode($kk);
    }

      public static function strReplaceEncode($str)
    {
        return self::jiaohuan_1(self::tihuan_1($str),3);
    }

    public static function strReplaceDecode($str)
    {
        return self::tihuan_2(self::jiaohuan_2($str,3));
    }


    public static function tihuan_1($str)
    {
        $encrypt_key = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-';
        $decrypt_key = self::$key;
        $enter = '';
        if (strlen($str) == 0) return false;
        for ($i = 0; $i < strlen($str); $i++) {
            for ($j = 0; $j < strlen($decrypt_key); $j++) {
                if ($str[$i] == $decrypt_key[$j]) {
                    $enter .= $encrypt_key[$j];
                    break;
                }
            }
        }
        return $enter;
    }

    public static function tihuan_2($str)
    {
        $encrypt_key = self::$key;
        $decrypt_key = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-';
        $enter = '';
        if (strlen($str) == 0) return false;
        for ($i = 0; $i < strlen($str); $i++) {
            for ($j = 0; $j < strlen($decrypt_key); $j++) {
                if ($str[$i] == $decrypt_key[$j]) {
                    $enter .= $encrypt_key[$j];
                    break;
                }
            }
        }
        return $enter;
    }

    public static function jiaohuan_1($str, $num)
    {
        $arr = str_split($str);
        $len = count($arr);
        $amount = ceil($len / $num);
        $str = array();
        for ($i = 0; $i < $amount; $i++) {
            $str[] = implode('', array_slice($arr, $i * $num, $num));
        }
        return implode('', array_reverse($str));
    }

    public static function jiaohuan_2($str, $num)
    {
        $arr = array_reverse(str_split($str));
        $amount = ceil(count($arr) / $num);
        $str = '';
        for ($i = 0; $i < $amount; $i++) {
            $str .= implode('', array_reverse(array_slice($arr, $i * $num, $num)));
        }
        return $str;
    }

    public static function encode_meipai_img_url($url)
    {
        
    }

    public static function decode_meipai_img_url($slug)
    {
        $code = substr($slug,0,strpos($slug,'.'));
        $url = self::strReplaceDecode($code);
        //mvimg11.meitudata.com/5c6670cba68233023.jpg!thumb320
        $url = 'http://mvimg11.meitudata.com/'.$url.'.jpg!thumb320';
        return $url;

    }

}

//$secret = 'd4c0aHRz0cDovL212dmlkZW8xMC5tZWl0dWRhdGEuY29tLzVhYmUzMTlkNjMwZDg0NTA3X0gyNjRfM181Lm1wVmEJRfvKQNA==';
//$secret = 'b0d1aHR0cDolaQgvL212dmlkZW8xMS5tZWl0dWRhdGEuY29tLzVjNjY3MTM2MDkwY2Q0NjYwLm1wNJlIjuA==';
//echo Utils::deSecret($secret);



