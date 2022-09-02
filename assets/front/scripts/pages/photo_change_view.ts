let newUrl = document.querySelector('[data-photos]');
let urlPrincipale = document.querySelector('[data-photo-principale]');

newUrl.addEventListener('mouseover', () => {

    // on récupère les attributs de l'élément
    const url: HTMLImageElement = document.querySelector('[data-photos]');

    // Explode la chaine de caractère pour récupérer le nom du fichier avec /
    const urlExplode: string[] = url.src.split('/');

    // Récupérer le dernier élément du tableau
    const lastElement: string = urlExplode[urlExplode.length - 1];

    const urlToChange: HTMLImageElement = document.querySelector('[data-photo-change]');

    // Explode la chaine de caractère pour récupérer le nom du fichier avec /
    const urlToChangeExplode: string[] = urlToChange.src.split('/');

    // Garder tout sauf le dernier élément du tableau
    urlToChangeExplode.pop();

    // Ajouter le dernier élément du tableau
    urlToChangeExplode.push(lastElement);

    // Transformer le tableau en chaine de caractère avec / entre les éléments
    const urlToChangeJoin: string = urlToChangeExplode.join('/');

    // Changer l'url de l'image
    urlToChange.src = urlToChangeJoin;

});

urlPrincipale.addEventListener('mouseover', () => {
    const url: HTMLImageElement = document.querySelector('[data-photo-principale]');
    const newUrlExplode: string[] = url.src.split('/');
    const lastElement: string = newUrlExplode[newUrlExplode.length - 1];
    const urlToChange: HTMLImageElement = document.querySelector('[data-photo-change]');
    const urlToChangeExplode: string[] = urlToChange.src.split('/');
    urlToChangeExplode.pop();
    urlToChangeExplode.push(lastElement);
    const urlToChangeJoin: string = urlToChangeExplode.join('/');
    urlToChange.src = urlToChangeJoin;
});

