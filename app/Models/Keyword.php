<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Libraries\Utils\Utils;

class Keyword extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'keywords';

    /**
     * @var array
     */
    protected $guarded = [];

    public function getslugAttribute()
    {
        return Utils::strReplaceEncode($this->md5);
    }

    public function geturlAttribute()
    {
        return route('frontend.search', $this->slug). '/';
    }

}