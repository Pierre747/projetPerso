window.onload = function() {
    let zindex = 10;
    const cards = document.querySelectorAll("div.card");
    // Cette ligne sélectionne tous les éléments HTML de type div avec la classe card et les stocke dans une constante appelée cards.

    // Pour chaque carte :
    cards.forEach(function(card) {

        card.querySelector("div.card-flap.flap2 > div > a").addEventListener('click', function (e) { console.log(e.target); window.location.href=e.target.href} );
        // Cette ligne ajoute un écouteur d'événements de clic à un élément enfant spécifique de chaque carte. Lorsqu'il est cliqué, il affiche l'élément cliqué dans la console et redirige la page vers l'URL de l'élément cliqué.

        card.addEventListener("click", function(e) {

            e.preventDefault();
            // Cela empêche le comportement par défaut du clic, ce qui est important, car on peut gérer le clic de manière personnalisée.

            let isShowing = false;

            if (this.classList.contains("show")) {
                // On vérifie si la classe "show" est présente dans la liste des classes de l'élément actuel.
                isShowing = true;
                // Si c'est le cas, on met à jour la variable isShowing à true.
            }

            const cardContainer = document.querySelector("div.cards");
            // On sélectionne les div avec la classe cards et on stocke ça dans une constante appelée cardContainer.

            if (cardContainer.classList.contains("showing")) {
                // On vérifie si la classe "showing" (contient) est présente dans la liste des classes de l'élément cardContainer.

                const showingCard = document.querySelector("div.card.show");
                // On sélectionne le premier élément HTML de type div avec les classes card et show, et le stocke dans une constante appelée showingCard.

                showingCard.classList.remove("show");
                // On supprime la classe "show" de l'élément showingCard.

                if (isShowing) {
                    // Si cette carte était affichée (showing), on la cache
                    cardContainer.classList.remove("showing");
                } else {
                    // Si cette carte n'est pas affichée (!showing), on l'y met
                    zindex = this.style.zIndex;
                    this.classList.add("show");
                }

                // J'incrémente le z-index
                zindex++;

            } else {
                // Dans le cas où je n'ai pas de carte en vue
                cardContainer.classList.add("showing");
                // J'applique le z-index
                zindex = this.style.zIndex;
                // Je rajoute la classe show
                this.classList.add("show");

                // J'incrémente le z-index
                zindex++;
            }
        });
    });
};
