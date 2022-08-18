<?php

namespace App\Enum;

Class EnumStatutPanier
{
    const CREEE = 100;
    const PAYEE = 200;
    const ABANDONNEE = 300;
    const PREPARATION = 400;
    const EXPEDIEE = 500;
    const REMBOURSEE = 600;
    const ANNULEE = 700;

    public static function getStatuts()
    {
        return [
            self::CREEE => 'Créée',
            self::PAYEE => 'Payée',
            self::ABANDONNEE => 'Abandonnée',
            self::PREPARATION => 'Préparée',
            self::EXPEDIEE => 'Expédiée',
            self::REMBOURSEE => 'Remboursée',
            self::ANNULEE => 'Annulée',
        ];
    }
}