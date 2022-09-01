<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class NotesExtension extends AbstractExtension
{

    public function getFilters()
    {
        return [
            // Le paramètre "notes" est le nom du filtre et is_safe pour renvoyer du HTML
            new TwigFilter('notes', [$this, 'notesFilter'], ['is_safe' => ['html']])
        ];
    }

    // Fonction pour l'affichage des étoiles suivant la moyenne des notes
    public function notesFilter($moyenne): string
    {
        if ($moyenne == 5 and $moyenne >= 4.5) {
            return
                '<p class="mx-3">
                <i class="yellowstar fas fa-star"></i>
                <i class="yellowstar fas fa-star"></i>
                <i class="yellowstar fas fa-star"></i>
                <i class="yellowstar fas fa-star"></i>
                <i class="yellowstar fas fa-star"></i>
                </p>';
        } else if ($moyenne < 4.5 and $moyenne >= 3.5) {
            return
                '<p class="mx-3">
                <i class="yellowstar fas fa-star"></i>
                <i class="yellowstar fas fa-star"></i>
                <i class="yellowstar fas fa-star"></i>
                <i class="yellowstar fas fa-star"></i>
                <i class="fas fa-star"></i>
                </p>';
        } else if ($moyenne < 3.5 and $moyenne >= 2.5) {
            return
                '<p class="mx-3">
                <i class="yellowstar fas fa-star"></i>
                <i class="yellowstar fas fa-star"></i>
                <i class="yellowstar fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                </p>';
        } else if ($moyenne < 2.5 and $moyenne >= 1.5) {
            return
                '<p class="mx-3">
                <i class="yellowstar fas fa-star"></i>
                <i class="yellowstar fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                </p>';
        } else if ($moyenne < 1.5 and $moyenne >= 0.5) {
            return
                '<p class="mx-3">
                 <i class="yellowstar fas fa-star"></i>
                 <i class="fas fa-star"></i>
                 <i class="fas fa-star"></i>
                 <i class="fas fa-star"></i>
                 <i class="fas fa-star"></i>
                 </p>';
        } else if ($moyenne < 0.5 and $moyenne > 0) {
            return
                '<p class="mx-3">
                     <i class="fas fa-star"></i>
                     <i class="fas fa-star"></i>
                     <i class="fas fa-star"></i>
                     <i class="fas fa-star"></i>
                     <i class="fas fa-star"></i>
                     </p>';
        } else {
            return
                '<p class="mx-3">
                     <i class="emptystar fas fa-star"></i>
                     <i class="emptystar fas fa-star"></i>
                     <i class="emptystar fas fa-star"></i>
                     <i class="emptystar fas fa-star"></i>
                     <i class="emptystar fas fa-star"></i>';
        }
    }
}