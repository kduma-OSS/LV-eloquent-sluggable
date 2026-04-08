<?php
declare(strict_types=1);

namespace KDuma\Eloquent;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use KDuma\Eloquent\Attributes\HasSlug;

trait Sluggable
{
    protected static function bootSluggable(): void
    {
        static::creating(function (Model $model): void {
            $field = $model->getSlugField();
            if ($model->{$field} === '' || $model->{$field} === null) {
                $model->generateSlug();
            }
        });
        static::updating(function (Model $model): void {
            $field = $model->getSlugField();
            if ($model->{$field} === '' || $model->{$field} === null) {
                $model->generateSlug();
            }
        });
    }

    public function getSlugField(): string
    {
        return $this->resolveSluggableConfig('field', 'slug_field', 'slug');
    }

    protected function getSluggableString(): string
    {
        if (method_exists($this, 'SluggableString')) {
            return $this->SluggableString();
        }

        $from = $this->resolveSluggableConfig('from', 'sluggable_from', 'title');

        return (string) $this->{$from};
    }

    protected function findSlug(string $title): string
    {
        $slug = Str::slug($title);
        $existingSlugs = $this->getExistingSlugs($slug);

        if (!$existingSlugs->contains($this->getSlugField(), $slug)) {
            return $slug;
        }

        for ($i = 1; $i <= 100; $i++) {
            $newSlug = $slug . '-' . $i;
            if (!$existingSlugs->contains($this->getSlugField(), $newSlug)) {
                return $newSlug;
            }
        }

        throw new \RuntimeException('Cannot create a unique slug after 100 attempts');
    }

    public function generateSlug(): void
    {
        $this->{$this->getSlugField()} = $this->findSlug($this->getSluggableString());
    }

    public function scopeWhereSlug(Builder $query, string $slug): Builder
    {
        return $query->where($this->getSlugField(), $slug);
    }

    public function getExistingSlugs(string $slug): Collection
    {
        $field = $this->getSlugField();

        return static::select($field)->where($field, 'like', $slug . '%')
            ->when($this->id, function (Builder $query): Builder {
                return $query->where('id', '<>', $this->id);
            })
            ->get();
    }

    private function resolveSluggableConfig(string $attrProperty, string $legacyProperty, mixed $default): mixed
    {
        $value = static::resolveClassAttribute(HasSlug::class, $attrProperty);
        if ($value !== null) {
            return $value;
        }

        if (property_exists($this, $legacyProperty)) {
            trigger_error(
                "Using \${$legacyProperty} on " . static::class . ' is deprecated. Use #[HasSlug] attribute instead.',
                E_USER_DEPRECATED,
            );

            return $this->{$legacyProperty} ?? $default;
        }

        return $default;
    }
}
