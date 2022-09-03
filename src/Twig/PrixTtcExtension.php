<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class PrixTtcExtension extends AbstractExtension
{

    public function getFilters()
    {
        return [
            // Le paramètre "ttc" est le nom du filtre et is_safe pour renvoyer du HTML
            new TwigFilter('prixTtc', [$this, 'prixTtc'])
        ];
    }

        // Filtre fonction avec 3 paramètres : le prix HT et le taux de TVA et une promotion
        public function prixTtc($prixHt, $tva, $promo): string
        {
            // Vérifie en premier si le produit n'à pas de promotion
            if ($promo == null) {
                // Si le produit n'a pas de promotion, on renvoie le prix TTC
                $prixTtc = $prixHt * (1 + $tva / 100);

                return number_format($prixTtc, 2, ',', ' ') . '€';
            } else {
                // Si le produit a une promotion, on renvoie le prix TTC avec la promotion
                $prixTtc = $prixHt * (1 + $tva / 100);
                $prixTtcPromo = $prixTtc - ($prixTtc * $promo / 100);

                return number_format($prixTtcPromo, 2, ',', ' ') . '€';
            }
        }
    }