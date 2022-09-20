interface TotalPrice{
    totalPrice: string;
}

// Fonction pour ajouter le prix total du panier à la session
function setUpClickEventTotalPriceCart(): void {
    const a: NodeListOf<HTMLDivElement> = document.querySelectorAll('[data-calculated-totalPrice]');

    if (a) {
        // on ajoute un événement click sur chaque bouton
        a.forEach((a) => {
            a.addEventListener('click', () => {

                // on récupère le prix total du panier
                const totalPrice: string = a.getAttribute('data-calculated-totalPrice');

                // creation de l'objet à envoyer au serveur
                let datasToSend: TotalPrice = {
                    totalPrice,
                };

                // on envoie la requête fetch dans le controller + l'objet à envoyer stringify et on attend la réponse
                fetch('/ajax/totalPrice/' + JSON.stringify(datasToSend))

                    .catch((e) => {
                        console.log('error' + e);
                    })

                    .then((response: Response) => {
                        return response.json() as Promise<TotalPrice>;
                    })
            });
        });
    }
}

window.addEventListener('load', () => {
    setUpClickEventTotalPriceCart();
});
