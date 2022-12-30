window.addEventListener('load', () => {

    // La div qui contient les résultats de la recherche
    const productResults = document.querySelector('.product-results');
    // Le formulaire de recherche
    const inputSearch = document.querySelector('.search-input-class') as HTMLInputElement;

    inputSearch.addEventListener('keyup', function () {

        let searchValue = inputSearch.value;

        // On envoie la requête AJAX
        fetch('/ajax/searchItems/' + JSON.stringify(searchValue), {
            method: "GET",
            headers: {
                "X-Requested-With": "XMLHttpRequest",
                "Content-Type": "application/json"
            },

        }).then(
            // On récupère la réponse en JSON
            response => response.json()).then(data => {
            productResults.classList.remove('d-none');
            if (data.error) {
                // On affiche l'erreur
                productResults.innerHTML = '<p class="mt-3 ms-4">' + data['error'] + '</p>';
            } else {
                if (data.message === 'ok') {
                    let html = '';
                    let link = '';
                    // on boucle sur les produits retournés
                    for (let i = 0; i < data['produits'].length; i++) {
                        // on concatène les balises html a afficher
                        html += '<li><a href="/produit/'+ data['produits'][i]['slug'] +'">' + data['produits'][i].libelle + '</a></li>';
                    }
                    // on affiche les produits
                    productResults.innerHTML = '<ul class="mt-3">' + html + '</ul>';
                } if (data.message === 'Aucun produit trouvé') {
                    // On affiche le message
                    productResults.innerHTML = '<p class="mt-3 ms-4">' + data['message'] + '</p>';
                } if (data.message === 'noSearch') {
                    // On masque la div
                    productResults.classList.add('d-none');
                }
            }
        }).catch(error => console.log(error));
    })

    // On cache la div si on clique en dehors et on vide le champ de recherche
    document.addEventListener('click', function (e) {
        if (e.target !== inputSearch) {
            productResults.classList.add('d-none');
            inputSearch.value = '';
        }
    })
})
