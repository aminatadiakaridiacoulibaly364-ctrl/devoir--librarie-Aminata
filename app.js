

// === Gestion du panier avec localStorage ===

// Charger le panier depuis localStorage (ou tableau vide)
let panier = JSON.parse(localStorage.getItem("panier")) || [];

// Mettre à jour l'affichage du panier et du compteur
function majPanier() {
    const compteur = document.querySelector("#compteur-panier");
    if (compteur) {
        compteur.textContent = panier.length;
    }

    const liste = document.querySelector("#liste-panier");
    const totalSpan = document.querySelector("#total-panier");

    // La zone panier n'existe que sur la page catalogue
    if (liste && totalSpan) {
        liste.innerHTML = "";
        let total = 0;

        panier.forEach((livre) => {
            const ligne = document.createElement("p");
            ligne.textContent = livre.titre + " - " + parseFloat(livre.prix).toFixed(2) + " €";
            liste.appendChild(ligne);
            total += parseFloat(livre.prix);
        });

        totalSpan.textContent = total.toFixed(2);
    }

    // Sauvegarder dans localStorage
    localStorage.setItem("panier", JSON.stringify(panier));
}

// Attacher les boutons "Ajouter au panier" (présents sur le catalogue)
const boutons = document.querySelectorAll(".btn-ajouter");
boutons.forEach((bouton) => {
    bouton.addEventListener("click", () => {
        const carte = bouton.closest(".carte-livre");
        const livre = {
            id: carte.dataset.id,
            titre: carte.dataset.titre,
            prix: carte.dataset.prix
        };
        panier.push(livre);
        majPanier();
    });
});

// Affichage initial (utile sur toutes les pages pour le compteur)
majPanier();
