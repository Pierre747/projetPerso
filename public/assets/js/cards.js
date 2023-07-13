window.onload = function() {
    let zindex = 10;
    const cards = document.querySelectorAll("div.card");
    cards.forEach(function(card) {
        card.addEventListener("click", function(e) {
            e.preventDefault();

            let isShowing = false;

            if (this.classList.contains("show")) {
                isShowing = true;
            }

            const cardContainer = document.querySelector("div.cards");

            if (cardContainer.classList.contains("showing")) {
                // a card is already in view
                const showingCard = document.querySelector("div.card.show");
                showingCard.classList.remove("show");

                if (isShowing) {
                    // this card was showing - reset the grid
                    cardContainer.classList.remove("showing");
                } else {
                    // this card isn't showing - get in with it
                    zindex = this.style.zIndex;
                    this.classList.add("show");
                }

                zindex++;

            } else {
                // no cards in view
                cardContainer.classList.add("showing");
                zindex = this.style.zIndex;
                this.classList.add("show");

                zindex++;
            }
        });
    });
};
