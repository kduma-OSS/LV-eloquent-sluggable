<?php

declare(strict_types=1);

use KDuma\Eloquent\Sluggable;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class SluggableTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    #[Test]
    #[DataProvider('provideExistingSlugs')]
    public function test_slug_generation(string|false $expectedSlug, \Illuminate\Support\Collection $existingSlugs): void
    {
        $testModel = Mockery::mock('TestModel[getExistingSlugs]', [2017, 'Lorem ipsum dolor sit ament...']);

        $testModel->shouldReceive('getExistingSlugs')
            ->once()
            ->withArgs(['2017-lorem-ipsum-dolor-sit-ament'])
            ->andReturn($existingSlugs);

        if ($expectedSlug === false) {
            $this->expectException(\RuntimeException::class);
            $this->expectExceptionMessage('Cannot create a unique slug after 100 attempts');
        }

        $testModel->generateSlug();

        if ($expectedSlug !== false) {
            $this->assertEquals($expectedSlug, $testModel->slug);
        }
    }

    public static function provideExistingSlugs(): array
    {
        return [
            'empty' => ['2017-lorem-ipsum-dolor-sit-ament', collect()],

            'one-duplicate' => ['2017-lorem-ipsum-dolor-sit-ament-1', collect([
                ['slug' => '2017-lorem-ipsum-dolor-sit-ament'],
            ])],

            'two-duplicates' => ['2017-lorem-ipsum-dolor-sit-ament-2', collect([
                ['slug' => '2017-lorem-ipsum-dolor-sit-ament'],
                ['slug' => '2017-lorem-ipsum-dolor-sit-ament-1'],
            ])],

            'fifty-duplicates' => ['2017-lorem-ipsum-dolor-sit-ament-51', collect(range(0, 50))->map(function (int $num): array {
                return ['slug' => '2017-lorem-ipsum-dolor-sit-ament' . ($num ? '-' . $num : '')];
            })],

            'hundred-duplicates' => [false, collect(range(0, 100))->map(function (int $num): array {
                return ['slug' => '2017-lorem-ipsum-dolor-sit-ament' . ($num ? '-' . $num : '')];
            })],
        ];
    }

    #[Test]
    public function test_slug_generation_without_custom_slug_generator(): void
    {
        $testModel = Mockery::mock('TestModelWithoutCustomSlugGenerator[getExistingSlugs]', ['Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec et...']);

        $testModel->shouldReceive('getExistingSlugs')
            ->once()
            ->withArgs(['lorem-ipsum-dolor-sit-amet-consectetur-adipiscing-elit-donec-et'])
            ->andReturn(collect());

        $testModel->generateSlug();

        $this->assertEquals('lorem-ipsum-dolor-sit-amet-consectetur-adipiscing-elit-donec-et', $testModel->slug);
    }
}

class TestModel
{
    use Sluggable;

    public string $slug = '';

    public function __construct(
        public int $year,
        public string $title,
    ) {}

    protected function SluggableString(): string
    {
        return $this->year . ' ' . $this->title;
    }
}

class TestModelWithoutCustomSlugGenerator
{
    use Sluggable;

    public string $slug = '';

    public function __construct(
        public string $title,
    ) {}
}
