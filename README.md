# Eloquent Sluggable

[![Latest Stable Version](https://poser.pugx.org/kduma/eloquent-sluggable/v/stable.svg)](https://packagist.org/packages/kduma/eloquent-sluggable)
[![Total Downloads](https://poser.pugx.org/kduma/eloquent-sluggable/downloads.svg)](https://packagist.org/packages/kduma/eloquent-sluggable)
[![License](https://poser.pugx.org/kduma/eloquent-sluggable/license.svg)](https://packagist.org/packages/kduma/eloquent-sluggable)

Eloquent trait for automatically generating unique slugs for Laravel models.

Full documentation: [opensource.duma.sh/libraries/php/eloquent-sluggable](https://opensource.duma.sh/libraries/php/eloquent-sluggable)

## Requirements

- PHP `^8.3`
- Laravel `^13.0`

## Installation

```bash
composer require kduma/eloquent-sluggable
```

## Usage

```php
use KDuma\Eloquent\Sluggable;
use KDuma\Eloquent\Attributes\HasSlug;

#[HasSlug(from: 'title', field: 'slug')]
class Post extends Model
{
    use Sluggable;
}
```

Add a `slug` column to your migration:

```php
$table->string('slug')->unique();
```

Slug is auto-generated on create. Find by slug with `Post::whereSlug($slug)`.
