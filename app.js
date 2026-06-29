

// === Gestion du panier et des favoris avec localStorage ===

const panier = JSON.parse(localStorage.getItem("panier")) || [];
const favoris = JSON.parse(localStorage.getItem("favoris")) || [];

function majCompteurs() {
    const compteurPanier = document.querySelector("#compteur-panier");
    const compteurFavoris = document.querySelector("#compteur-favoris");
    if (compteurPanier) compteurPanier.textContent = panier.length;
    if (compteurFavoris) compteurFavoris.textContent = favoris.length;
}

function afficherPanier() {
    const liste = document.querySelector("#liste-panier");
    const totalSpan = document.querySelector("#total-panier");
    if (!liste || !totalSpan) return;

    liste.innerHTML = "";
    let total = 0;

    panier.forEach((livre, index) => {
        const article = document.createElement("article");
        article.className = "ligne-panier";
        article.innerHTML = `
            <span>${livre.titre} - ${parseFloat(livre.prix).toFixed(2)} €</span>
            <button class="btn-retirer" data-index="${index}">Retirer</button>
        `;
        liste.appendChild(article);
        total += parseFloat(livre.prix);
    });

    totalSpan.textContent = total.toFixed(2);
    document.querySelectorAll(".btn-retirer").forEach((btn) => {
        btn.addEventListener("click", () => {
            panier.splice(parseInt(btn.dataset.index, 10), 1);
            sauvegarderEtRafraichir();
        });
    });
}

function afficherFavoris() {
    const liste = document.querySelector("#liste-favoris");
    if (!liste) return;

    liste.innerHTML = "";

    favoris.forEach((livre, index) => {
        const article = document.createElement("article");
        article.className = "ligne-favori";
        article.innerHTML = `
            <span>${livre.titre} — ${livre.auteur}</span>
            <div>
                <button class="btn-ajouter-panier" data-index="${index}">Ajouter au panier</button>
                <button class="btn-retirer" data-index="${index}">Retirer</button>
            </div>
        `;
        liste.appendChild(article);
    });

    document.querySelectorAll(".btn-ajouter-panier").forEach((btn) => {
        btn.addEventListener("click", () => {
            const livre = favoris[parseInt(btn.dataset.index, 10)];
            panier.push({ id: livre.id, titre: livre.titre, prix: livre.prix });
            sauvegarderEtRafraichir();
        });
    });

    document.querySelectorAll(".ligne-favori .btn-retirer").forEach((btn) => {
        btn.addEventListener("click", () => {
            favoris.splice(parseInt(btn.dataset.index, 10), 1);
            sauvegarderEtRafraichir();
        });
    });
}

function sauvegarderEtRafraichir() {
    localStorage.setItem("panier", JSON.stringify(panier));
    localStorage.setItem("favoris", JSON.stringify(favoris));
    majCompteurs();
    afficherPanier();
    afficherFavoris();
}

function ajouterAuxFavoris(livre) {
    const existe = favoris.some((item) => item.id === livre.id);
    if (!existe) {
        favoris.push(livre);
        sauvegarderEtRafraichir();
    }
}

function attacherActionsCatalogue() {
    document.querySelectorAll(".btn-ajouter").forEach((bouton) => {
        bouton.addEventListener("click", () => {
            const carte = bouton.closest(".carte-livre");
            const livre = {
                id: carte.dataset.id,
                titre: carte.dataset.titre,
                prix: carte.dataset.prix
            };
            panier.push(livre);
            sauvegarderEtRafraichir();
        });
    });

    document.querySelectorAll(".btn-favori").forEach((bouton) => {
        bouton.addEventListener("click", () => {
            const carte = bouton.closest(".carte-livre");
            const livre = {
                id: carte.dataset.id,
                titre: carte.dataset.titre,
                auteur: carte.dataset.auteur,
                prix: carte.dataset.prix,
                image: carte.dataset.image
            };
            ajouterAuxFavoris(livre);
        });
    });
}

majCompteurs();
attacherActionsCatalogue();
sauvegarderEtRafraichir();
