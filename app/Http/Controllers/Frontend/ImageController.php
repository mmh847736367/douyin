<?php


namespace App\Http\Controllers\Frontend;

use Libraries\Utils\Utils;

class ImageController
{
    public function meipai($slug)
    {
        $src_path = public_path('images/ico_xia.png');
//        if($slug == 'load.png') {
//            $img = \Image::make(public_path('images/load.png'));
//            return $img->response();
//        }
        $url = Utils::decode_meipai_img_url($slug);
        $image = \Image::make($url);
        $image->insert($src_path,'bottom-right', 10, 10);
        return $image->response();
    }
}