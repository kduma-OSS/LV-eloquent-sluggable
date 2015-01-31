<?php
namespace KDuma\Eloquent;

use Illuminate\Support\Str;


/**
 * Class Slugabble
 * @package KDuma\Eloquent
 */
trait Slugabble {


    /**
     * @return mixed
     */
    protected function getSluggableString(){
        if(method_exists($this, 'SluggableString'))
            return $this->SluggableString();
        return $this->title;
    }


    /**
     * @param $title
     * @param int $duplicates_count
     * @return string
     */
    protected static function makeSlug($title, $duplicates_count = 0)
    {
        $duplicates_count = (int) $duplicates_count;

        $slug = $title = Str::slug($title);

        if($slug == $this->slug)
            return $slug;

        if ($duplicates_count > 0) {
            $slug = $slug.'-'.$duplicates_count;
            $rowCount = \DB::table($this->getTable())->where('slug', $slug)->count();
            if ($rowCount > 0) {
                return static::makeSlug($title, ++$duplicates_count);
            } else {
                return $slug;
            }
        } else {
            $rowCount = \DB::table($this->getTable())->where('slug', $title)->count();
            if ($rowCount > 0) {
                return static::makeSlug($title, ++$duplicates_count);
            } else {
                return $title;
            }
        }
    }

    /**
     * Generates slug
     */
    public function newSlug(){
        $this->slug = $this->makeSlug($this->getSluggableString());
    }

    /**
     * @param array $options
     */
    public function save(array $options=[]){
        if($this->slug == '')
            $this->newSlug();
        parent::save($options);
    }

    /**
     * @param $query
     * @param $slug
     * @return bool|int
     */
    public function scopeWhereToken($query, $slug)
    {
        return $query->where('slug', $slug);
    }
}