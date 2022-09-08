
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
                        if (data.addOk) {
                            alert('Favoris ajouté');
                        } else {
                            alert('Favoris supprimé');
                        }
                    });
            });
        });
    }
}

window.addEventListener('load', () => {
    setUpClickEventAddFavorite();
});