<?php


namespace App\Repositories;

use App\Models\Keyword;
use App\Exceptions\GeneralException;
use Carbon\Carbon;
use Libraries\Utils\Utils;

class KeywordRepository extends BaseRepository
{
    /**
     * @return string
     */
    public function model()
    {
        return Keyword::class;
    }

    /**
     * @param $slug
     * @return mixed
     * @throw Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function getBySlug($slug)
    {
        $md5 = Utils::strReplaceDecode($slug);

        return $this->model
            ->where('md5', $md5)->firstOrFail();
    }


    /**
     * @param $name
     * @return Keyword
     */
    public function create(array $data)
    {
        if($this->keywordExists($data['name'])) {
            if(isset($data['type'])) {
                $this->model->where('name',$data['name'])
                    ->update($data);
            }
           return $this->model->where($data)->first();
        }

        $data['md5'] = substr(md5($data['name']),8,16);

        return $this->model
            ->create($data);

    }




    public function createMultiple(array $data)
    {
        $models = collect();

        foreach ($data as $d) {
            $model = $this->create($d);
            if(!empty($model)) {
                $models->push($model);
            }
        }

        return $models;
    }


    /**
     * @param $name
     * @return bool
     */
    public function keywordExists($name)
    {
        return $this->model
                ->where('name', $name)
                ->count() > 0;
    }

}