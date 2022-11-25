interface Paiement {
    choicePay: string;
}

function setUpClickEventChoicePay(): void {
    const radios: NodeListOf<HTMLInputElement> = document.querySelectorAll('[data-choice-pay]');
    if (radios) {
        radios.forEach((radio) => {
            radio.addEventListener('click', () => {
                const choicePay: string = radio.getAttribute('data-choice-pay');
                let datasToSend: Paiement = {
                    choicePay
                };
                fetch('/ajax/choicePay/' + JSON.stringify(datasToSend))
                    .catch((e) => {
                        console.log('error' + e);
                    })
                    .then((response: Response) => {
                        return response.json() as Promise<Paiement>;
                    })
            });
        });
    }
}

window.addEventListener('load', () => {
    setUpClickEventChoicePay();
});
