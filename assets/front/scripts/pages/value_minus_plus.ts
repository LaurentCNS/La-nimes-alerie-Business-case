
// Pour accéder aux elements, on utilise la methode queryElementById
let minus = document.getElementById('minus');
let plus = document.getElementById('plus');

// on ajoute un événement click le bouton moins
minus?.addEventListener('click', function handleClick(event) {

    // on récupère le contenu de data-input-add-produit
    let inputValue: HTMLInputElement = document.querySelector("[data-input-add-produit]");

    // récupérer seulement la valeur et assigner à une variable
    let value: number = inputValue.valueAsNumber;


    // si la valeur est supérieure à 1 on la diminue
    if (value > 1) {
        value--;
        // on met à jour la value
        inputValue.value = value.toString();
    }
});

// on ajoute un événement click le bouton plus
plus?.addEventListener('click', function handleClick(event) {

    const inputValue: HTMLInputElement = document.querySelector("[data-input-add-produit]");
    let value: number = inputValue.valueAsNumber;

    // Récupérer le stock du produit
    let stock: string = inputValue.max;
    // transformer la valeur en nombre
    let maxValue: number = parseInt(stock);

    // si la valeur est inférieure à 100 on l'augmente de 1
    if (value <= 99 && value < maxValue) {
        value++;
        inputValue.value = value.toString();
    }
});