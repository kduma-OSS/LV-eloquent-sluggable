# L5-eloquent-sluggable
[![Latest Stable Version](https://poser.pugx.org/kduma/eloquent-sluggable/v/stable.svg)](https://packagist.org/packages/kduma/eloquent-sluggable) 
[![Total Downloads](https://poser.pugx.org/kduma/eloquent-sluggable/downloads.svg)](https://packagist.org/packages/kduma/eloquent-sluggable) 
[![Latest Unstable Version](https://poser.pugx.org/kduma/eloquent-sluggable/v/unstable.svg)](https://packagist.org/packages/kduma/eloquent-sluggable) 
[![License](https://poser.pugx.org/kduma/eloquent-sluggable/license.svg)](https://packagist.org/packages/kduma/eloquent-sluggable)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/5cc51ad6-606c-43d5-bb3b-6f0bbde61dd0/mini.png)](https://insight.sensiolabs.com/projects/5cc51ad6-606c-43d5-bb3b-6f0bbde61dd0)
[![StyleCI](https://styleci.io/repos/30116299/shield?branch=master)](https://styleci.io/repos/30116299)
[![Build Status](https://travis-ci.org/kduma/L5-eloquent-sluggable.svg?branch=master)](https://travis-ci.org/kduma/L5-eloquent-sluggable)


Eases using and generating slugs Laravel Eloquent models.

# Setup
Add the package to the require section of your composer.json and run `composer update`

    "kduma/eloquent-sluggable": "^1.1"

# Prepare models
Inside your model (not on top of file) add following lines:
    
    use \KDuma\Eloquent\Slugabble;

Optionally you can add also `SluggableString` function which will return string from which slug will be made (default it uses `title` field):

    protected function SluggableString(){
        return $this->year.' '.$this->title;
    }  

In database create `slug` string field. If you use migrations, you can use following snippet:

    $table->string('slug')->unique();

# Usage
By default it generates slug on first save.

- `$model->newSlug()` - Generate new slug. (Remember to save it by yourself)
- `Model::whereSlug($slug)->first()` - Find by slug. (`whereSlug` is query scope)
   

# Eric L. Barnes

A special thanks to [Eric L. Barnes](https://dotdev.co/creating-unique-title-slugs-with-laravel/), an original code creator that this package is based on.

# Packagist
View this package on Packagist.org: [kduma/eloquent-sluggable](https://packagist.org/packages/kduma/eloquent-sluggable)
