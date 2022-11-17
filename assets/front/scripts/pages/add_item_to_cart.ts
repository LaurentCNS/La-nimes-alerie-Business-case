// Interface pour les données à enregistrer
interface ItemAndQty {
    produitId: string;
    qty: number;
}

interface ResponseCart {
    qtyTotale: string;
}

// fonction pour ajouter un item
function setUpClickEventAddItem(): void {

    const buttons: NodeListOf<HTMLButtonElement> = document.querySelectorAll('[data-produit-id]');

    if (buttons) {
        // on ajoute un événement click sur chaque bouton
        buttons.forEach((btn) => {
            btn.addEventListener('click', () => {

                // on récupère l'id du produit
                const produitId: string = btn.getAttribute('data-produit-id');

                // on récupère la quantité du produit
                const inputQty: HTMLInputElement = document.querySelector("[data-input-add-produit='"+produitId+"']");

                // creation de l'objet à envoyer au serveur
                let datasToSend: ItemAndQty = {
                    produitId,
                    qty: inputQty.valueAsNumber
                };

                // on envoie la requête fetch dans le controller + l'objet à envoyer stringify et on attend la réponse
                fetch('/ajax/addItemToCart/' + JSON.stringify(datasToSend))

                    .catch((e) => {
                        console.log('error' + e);
                    })

                    .then((response: Response) => {
                        return response.json() as Promise<ResponseCart>;
                    })

                    // on récupère la réponse du serveur et on met à jour la quantité du panier
                    .then((data) => {
                        // On renvoie la quantité totale à afficher dans le panier
                        const qtyCart: HTMLParagraphElement = document.querySelector('[data-cart-item]');
                        let singOrPlural: string = ' article';
                        if (parseInt(data.qtyTotale) > 1 && parseInt(data.qtyTotale) < 10) {
                            singOrPlural += 's';
                            qtyCart.classList.remove('value');
                            qtyCart.classList.add('values');
                        } else if (parseInt(data.qtyTotale) >= 10 && parseInt(data.qtyTotale) < 100) {
                            singOrPlural += 's';
                            qtyCart.classList.remove('value');
                            qtyCart.classList.add('valuesDouble');
                        } else if(parseInt(data.qtyTotale) > 99){
                            singOrPlural += 's';
                            qtyCart.classList.remove('valuesDouble');
                            qtyCart.classList.add('valuesTriples');
                            data.qtyTotale = '99+';
                        }
                        qtyCart.innerText = data.qtyTotale + singOrPlural;
                    });
            });
        });
    }
}

// Lancer la fonction au chargement de la page
window.addEventListener('load', () => {
    setUpClickEventAddItem();
});