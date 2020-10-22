<?php


namespace App\Twig;


use App\Entity\LikeNotification;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension implements GlobalsInterface
{

    private $locale;

    /**
     * @param string $local
     */
    public function __construct(string $locale)
    {
        $this->locale = $locale;
    }

    public function getFilters()
    {
        return [
            new TwigFilter('price',[$this, 'priceFilter'])
        ];
    }

    public function priceFilter($number)
    {
        return '$'.number_format($number,2,'.','');
    }

    public function getGlobals(): array
    {
        return [
            'local' => $this->locale
        ];
    }

    public function getTests()
    {
        return [
            new \Twig\TwigTest('like', function ($obj){
                return $obj instanceof LikeNotification;
            })
        ];
    }
}