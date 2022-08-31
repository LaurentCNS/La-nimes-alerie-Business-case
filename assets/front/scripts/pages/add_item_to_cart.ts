
interface ItemQty {
    produitId: string;
    qty: number;
}

interface ResponseCart {
    qtyTotale: string;
}

function setUpClickEventAddItem(): void {
    const buttons: NodeListOf<HTMLButtonElement> = document.querySelectorAll('[data-produit-id]');
    if (buttons) {
        buttons.forEach((btn) => {
            btn.addEventListener('click', () => {
                const produitId: string = btn.getAttribute('data-produit-id');
                const inputQty: HTMLInputElement = document.querySelector("[data-input-add-produit='"+produitId+"']");
                let datasToSend: ItemQty = {
                    produitId,
                    qty: inputQty.valueAsNumber
                };
                fetch('/ajax/addItemToCart/' + JSON.stringify(datasToSend))
                    .catch((e) => {
                        console.log('error' + e);
                    })
                    .then((response: Response) => {
                        return response.json() as Promise<ResponseCart>;
                    })
                    .then((data) => {
                        const qtyCart: HTMLParagraphElement = document.querySelector('[data-cart-item]');
                        qtyCart.innerText = data.qtyTotale;
                    });
            });
        });
    }
}

window.addEventListener('load', () => {
    setUpClickEventAddItem();
});