
// Interface pour les données à enregistrer
interface ProduitId {
    produitId: string;
}

interface ResponseFavorite {
    addOk: string;
}

// Fonction pour ajouter en favoris
function setUpClickEventAddFavorite(): void {
    const a: NodeListOf<HTMLDivElement> = document.querySelectorAll('[data-input-favorite-produit]');

    if (a) {
        // on ajoute un événement click sur le lien
        a.forEach((a) => {
            a.addEventListener('click', () => {

                // on récupère l'id du produit
                const produitId: string = a.getAttribute('data-input-favorite-produit');

                // creation de l'objet à envoyer au serveur
                let datasToSend: ProduitId = {
                    produitId,
                };

                // on envoie la requête fetch dans le controller + l'objet à envoyer stringify et on attend la réponse
                fetch('/ajax/addToFavorite/' + JSON.stringify(datasToSend))

                    .catch((e) => {
                        console.log('error' + e);
                    })

                    .then((response: Response) => {
                        return response.json() as Promise<ResponseFavorite>;
                    })

                    // Retour de la promesse pour gérer les classes css
                    .then((data) => {
                        const heart: HTMLElement = document.querySelector('[data-heart]');
                        if (data.addOk) {
                            if (heart) {
                                heart.classList.add('overrideHeart');
                            }
                        } else {
                            if (heart) {
                                heart.classList.remove('overrideHeart');
                            }
                        }
                        const bg: HTMLElement = document.querySelector('[data-bg]');
                        if (data.addOk) {
                            if (bg) {
                                bg.classList.add('overrideCircle');
                            }
                        } else {
                            if (bg) {
                                bg.classList.remove('overrideCircle');
                            }
                        }
                    });
            });
        });
    }
}

window.addEventListener('load', () => {
    setUpClickEventAddFavorite();
});
