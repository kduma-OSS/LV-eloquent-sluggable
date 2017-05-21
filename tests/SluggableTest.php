<?php

class SluggableTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        Mockery::close();
    }

    /**
     * @test
     * @dataProvider provideExistingSlugs
     */
    public function test_slug_generation($ExpectedSlug, $ExistingSlugs)
    {
        $TestModel = Mockery::mock('TestModel[getExistingSlugs]', [2017, 'Lorem ipsum dolor sit ament...']);

        $TestModel->shouldReceive('getExistingSlugs')
            ->once()
            ->withArgs(['2017-lorem-ipsum-dolor-sit-ament'])
            ->andReturn($ExistingSlugs);

        if(!$ExpectedSlug){
            $this->expectException('Exception');
            $this->expectExceptionMessage('Can not create a unique slug');
        }

        $TestModel->generateSlug();

        if($ExpectedSlug)
            $this->assertEquals($ExpectedSlug, $TestModel->slug);
    }

    public function provideExistingSlugs() {
        return [
            'empty' => ['2017-lorem-ipsum-dolor-sit-ament', collect()],

            'one-duplicate' => ['2017-lorem-ipsum-dolor-sit-ament-1', collect([
                ['slug' => '2017-lorem-ipsum-dolor-sit-ament'],
            ])],

            'two-duplicates' => ['2017-lorem-ipsum-dolor-sit-ament-2', collect([
                ['slug' => '2017-lorem-ipsum-dolor-sit-ament'],
                ['slug' => '2017-lorem-ipsum-dolor-sit-ament-1'],
            ])],

            'fifthy-duplicates' => ['2017-lorem-ipsum-dolor-sit-ament-51', collect(range(0, 50))->map(function($num){
                return ['slug' => '2017-lorem-ipsum-dolor-sit-ament'.($num ? '-'.$num : '')];
            })],

            'hundread-duplicates' => [false, collect(range(0, 100))->map(function($num){
                return ['slug' => '2017-lorem-ipsum-dolor-sit-ament'.($num ? '-'.$num : '')];
            })],
        ];
    }

    /**
     * @test
     */
    public function test_slug_generation_without_custom_slug_generator()
    {
        $TestModel = Mockery::mock('TestModelWithoutCustomSlugGenerator[getExistingSlugs]', ['Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec et...']);

        $TestModel->shouldReceive('getExistingSlugs')
            ->once()
            ->withArgs(['lorem-ipsum-dolor-sit-amet-consectetur-adipiscing-elit-donec-et'])
            ->andReturn(collect());

        $TestModel->generateSlug();

        $this->assertEquals('lorem-ipsum-dolor-sit-amet-consectetur-adipiscing-elit-donec-et', $TestModel->slug);
    }

}


/**
 * Class TestModel
 */
class TestModel {
    use \KDuma\Eloquent\Slugabble;

    /**
     * @var integer
     */
    public $year;

    /**
     * @var string
     */
    public $title;

    /**
     * @var string
     */
    public $slug;

    /**
     * TestModel constructor.
     * @param int $year
     * @param string $title
     */
    public function __construct($year, $title)
    {
        $this->year = $year;
        $this->title = $title;
    }

    /**
     * @return string
     */
    protected function SluggableString(){
        return $this->year.' '.$this->title;
    }
}

/**
 * Class TestModelWithoutCustomSlugGenerator
 */
class TestModelWithoutCustomSlugGenerator {
    use \KDuma\Eloquent\Slugabble;

    /**
     * @var string
     */
    public $title;

    /**
     * @var string
     */
    public $slug;

    /**
     * TestModelWithoutCustomSlugGenerator constructor.
     * @param string $title
     */
    public function __construct($title)
    {
        $this->title = $title;
    }
}
