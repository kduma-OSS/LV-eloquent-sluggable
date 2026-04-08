# Eloquent Sluggable

[![Latest Stable Version](https://poser.pugx.org/kduma/eloquent-sluggable/v/stable.svg)](https://packagist.org/packages/kduma/eloquent-sluggable)
[![Total Downloads](https://poser.pugx.org/kduma/eloquent-sluggable/downloads.svg)](https://packagist.org/packages/kduma/eloquent-sluggable)
[![License](https://poser.pugx.org/kduma/eloquent-sluggable/license.svg)](https://packagist.org/packages/kduma/eloquent-sluggable)

Eases using and generating slugs for Laravel Eloquent models.

Check full documentation here: [opensource.duma.sh/libraries/php/eloquent-sluggable](https://opensource.duma.sh/libraries/php/eloquent-sluggable)

## Requirements

- PHP `^8.3`
- Laravel `^13.0`

## Installation

```bash
composer require kduma/eloquent-sluggable
```

## Setup

Add the `Sluggable` trait to your model:

```php
use KDuma\Eloquent\Sluggable;

class Post extends Model
{
    use Sluggable;
}
```

In your migration, create a `slug` column:

```php
$table->string('slug')->unique();
```

## Configuration

### New style — PHP Attribute (recommended)

```php
use KDuma\Eloquent\Sluggable;
use KDuma\Eloquent\Attributes\HasSlug;

#[HasSlug(from: 'name', field: 'slug')]
class Product extends Model
{
    use Sluggable;
}
```

### Old style — model properties (deprecated, triggers `E_USER_DEPRECATED`)

```php
class Post extends Model
{
    use Sluggable;

    protected string $sluggable_from = 'name';  // field to generate slug from (default: 'title')
    protected string $slug_field = 'slug';       // slug column name (default: 'slug')
}
```

### Default behaviour (no configuration)

Without any configuration, the trait reads from the `title` field and stores the slug in the `slug` column.

```php
class Post extends Model
{
    use Sluggable; // uses $post->title → stores in $post->slug
}
```

## Usage

- Slug is automatically generated on `create` and regenerated on `update` if the slug field is empty.
- `$model->generateSlug()` — manually trigger slug generation (remember to `save()` afterwards)
- `Model::whereSlug($slug)` — query scope to find by slug
- `$model->getSlugField()` — returns the configured slug field name

### Custom slug source

Override `SluggableString()` on your model for full control:

```php
class Post extends Model
{
    use Sluggable;

    protected function SluggableString(): string
    {
        return $this->year . ' ' . $this->title;
    }
}
```

## Packagist

[kduma/eloquent-sluggable](https://packagist.org/packages/kduma/eloquent-sluggable)
