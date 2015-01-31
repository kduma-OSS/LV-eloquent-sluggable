# L5-eloquent-sluggable
Eases using and generating slugs Laravel Eloquent models.

# Setup
Add the package to the require section of your composer.json and run `composer update`

    "kduma/eloquent-sluggable": "~1.0"

# Prepare models
In your model add following lines:
    
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
   

# euantorano

A special thanks to [euantorano](http://forumsarchive.laravel.io/viewtopic.php?id=6629#6), an original code creator that this package is based on.

# Packagist
View this package on Packagist.org: [kduma/eloquent-sluggable](https://packagist.org/packages/kduma/eloquent-sluggable)