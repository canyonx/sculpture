<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AmountExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            // on crée un filtre amount, retourne la fonction dans cette classe qui porte le nom amount
            new TwigFilter('amount', [$this, 'amount'])
        ];
    }

    public function amount($value)
    {
        // Transformer 12345 en 123,45 €
        $finalValue = $value / 100;
        $finalValue = number_format($finalValue, 2, ',', ' ');

        return $finalValue . ' €';
    }
}
