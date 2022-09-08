
// Interface pour les données à enregistrer
interface ProduitId {
    produitId: string;
}

interface ResponseFavorite {
    addOk: string;
}

// Fonction pour ajouter en favoris
function setUpClickEventAddFavorite(): void {
    const a: NodeListOf<HTMLLinkElement> = document.querySelectorAll('[data-input-favorite-produit]');

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

                    .then((data) => {
                        const heart: HTMLElement = document.querySelector('[data-heart]');
                        if (data.addOk) {
                            if (heart) {
                                heart.classList.add('text-danger');
                                heart.classList.add('fa-2x');
                            }
                        } else {
                            if (heart) {
                                heart.classList.remove('text-danger');
                                heart.classList.remove('fa-2x');
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