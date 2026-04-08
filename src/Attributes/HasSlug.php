<?php
declare(strict_types=1);

namespace KDuma\Eloquent\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class HasSlug
{
    public function __construct(
        public readonly string $from = 'title',
        public readonly string $field = 'slug',
    ) {}
}
