<?php

namespace KDuma\Eloquent;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Slugabble.
 */
trait Slugabble
{
    /**
     * Boot the trait.
     *
     * @codeCoverageIgnore Eloquent specific code
     */
    protected static function bootSlugabble()
    {
        static::creating(function (Model $model) {
            if ($model->slug == '') {
                $model->generateSlug();
            }
        });
        static::updating(function (Model $model) {
            if ($model->slug == '') {
                $model->generateSlug();
            }
        });
    }

    /**
     * @return mixed
     */
    protected function getSluggableString()
    {
        if (method_exists($this, 'SluggableString')) {
            return $this->SluggableString();
        }

        return $this->title;
    }

    /**
     * @param $title
     * @return string
     * @throws \Exception
     */
    protected function findSlug($title)
    {
        // Normalize the title
        $slug = Str::slug($title);

        // Get any that could possibly be related.
        // This cuts the queries down by doing it once.
        $existingSlugs = $this->getExistingSlugs($slug);

        // If we haven't used it before then we are all good.
        if (! $existingSlugs->contains('slug', $slug)){
            return $slug;
        }

        // Just append numbers like a savage until we find not used.
        for ($i = 1; $i <= 100; $i++) {
            $newSlug = $slug.'-'.$i;
            if (! $existingSlugs->contains('slug', $newSlug)) {
                return $newSlug;
            }
        }

        throw new \Exception('Can not create a unique slug');
    }

    /**
     * Generates slug.
     */
    public function generateSlug()
    {
        $this->slug = $this->findSlug($this->getSluggableString());
    }

    /**
     * @param $query
     * @param $slug
     * @return bool|int
     *
     * @codeCoverageIgnore Eloquent specific code
     */
    public function scopeWhereSlug($query, $slug)
    {
        return $query->where('slug', $slug);
    }

    /**
     * @param $slug
     * @return mixed
     *
     * @codeCoverageIgnore Eloquent specific code
     */
    public function getExistingSlugs($slug)
    {
        return self::select('slug')->where('slug', 'like', $slug . '%')
            ->when($this->id, function ($query) {
                return $query->where('id', '<>', $this->id);
            })
            ->get();
    }
}
