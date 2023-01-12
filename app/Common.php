<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Cache;

class Common{

	public static function khongdau($str, $separator = ' ', $lowercase = TRUE){
        if($separator == 'dash'){
            $separator = '-';
        }else{
            if($separator == 'underscore'){
                $separator = '_';
            }
        }
        $q_separator = preg_quote($separator);
        $trans       = array(
            '&.+?;'                               => '',
            'à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ'   => 'a',
            'è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ'               => 'e',
            'ì|í|ị|ỉ|ĩ'                           => 'i',
            'ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ'   => 'o',
            'ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ'               => 'u',
            'ỳ|ý|y|ỷ|ỹ'                           => 'y',
            'À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ|A' => 'a',
            'È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ|E'             => 'e',
            'Ì|Í|Ị|Ỉ|Ĩ|I'                         => 'i',
            'Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ|O' => 'o',
            'Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ|U'             => 'u',
            'Ỳ|Ý|Y|Ỷ|Ỹ|Y'                         => 'y',
            'đ|Đ'                                 => 'd',
            ' '                                   => $separator,
            '='                                   => $separator,
            '/'                                   => $separator,
            '[^a-z0-9 _-]'                        => '',
            '\s+'                                 => $separator,
            '(' . $q_separator . ')+'             => $separator
        );
        $str         = strip_tags($str);
        foreach($trans as $key => $val){
            $str = preg_replace("#" . $key . "#i", $val, $str);
        }
        if($lowercase === TRUE){
            $str = strtolower($str);
        }
        return trim($str, $separator);
    }
}
