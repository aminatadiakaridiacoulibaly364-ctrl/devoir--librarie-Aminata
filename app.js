

// === Gestion du panier et des favoris avec localStorage ===

const panier = JSON.parse(localStorage.getItem("panier")) || [];
const favoris = JSON.parse(localStorage.getItem("favoris")) || [];

function majCompteurs() {
    const compteurPanier = document.querySelector("#compteur-panier");
    const compteurFavoris = document.querySelector("#compteur-favoris");
    if (compteurPanier) {
        const totalQte = panier.reduce((sum, item) => sum + (item.qty || 1), 0);
        compteurPanier.textContent = totalQte;
    }
    if (compteurFavoris) compteurFavoris.textContent = favoris.length;
}

function afficherPanier() {
    const liste = document.querySelector("#liste-panier");
    const totalSpan = document.querySelector("#total-panier");
    if (!liste || !totalSpan) return;

    liste.innerHTML = "";
    let total = 0;

    panier.forEach((livre, index) => {
        const quantite = livre.qty || 1;
        const article = document.createElement("article");
        article.className = "ligne-panier";
        article.innerHTML = `
            <div class="ligne-panier-info">
                <span class="titre-panier">${livre.titre}</span>
                <span>${parseFloat(livre.prix).toFixed(2)} €</span>
            </div>
            <div class="quantite-controls">
                <button class="btn-decrement" data-index="${index}">-</button>
                <span class="quantite">${quantite}</span>
                <button class="btn-increment" data-index="${index}">+</button>
                <button class="btn-retirer" data-index="${index}">Retirer</button>
            </div>
        `;
        liste.appendChild(article);
        total += parseFloat(livre.prix) * quantite;
    });

    totalSpan.textContent = total.toFixed(2);
    document.querySelectorAll(".btn-retirer").forEach((btn) => {
        btn.addEventListener("click", () => {
            panier.splice(parseInt(btn.dataset.index, 10), 1);
            sauvegarderEtRafraichir();
        });
    });
    document.querySelectorAll(".btn-increment").forEach((btn) => {
        btn.addEventListener("click", () => {
            const index = parseInt(btn.dataset.index, 10);
            panier[index].qty = (panier[index].qty || 1) + 1;
            sauvegarderEtRafraichir();
        });
    });
    document.querySelectorAll(".btn-decrement").forEach((btn) => {
        btn.addEventListener("click", () => {
            const index = parseInt(btn.dataset.index, 10);
            panier[index].qty = Math.max(1, (panier[index].qty || 1) - 1);
            sauvegarderEtRafraichir();
        });
    });
    const boutonValider = document.querySelector('#btn-valider');
    const etatCommande = document.querySelector('#etat-command');
    if (boutonValider) {
        boutonValider.disabled = panier.length === 0;
        boutonValider.onclick = () => {
            if (panier.length === 0) return;
            if (!confirm('Valider la commande ?')) return;
            panier.length = 0;
            sauvegarderEtRafraichir();
            if (etatCommande) {
                etatCommande.textContent = 'Commande validée ! Merci pour votre achat.';
                etatCommande.style.color = 'var(--bordeaux)';
            }
        };
    }
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
        // Utiliser directement la fonction sécurisée au lieu d'un .push brut
        ajouterAuPanier({ id: livre.id, titre: livre.titre, prix: livre.prix });
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

function estFavori(id) {
    return favoris.some((item) => item.id === id);
}

function toggleFavori(livre) {
    const index = favoris.findIndex((item) => item.id === livre.id);
    if (index >= 0) {
        favoris.splice(index, 1);
        return false;
    }

    favoris.push(livre);
    return true;
}

function actualiserCoeurs() {
    document.querySelectorAll(".btn-heart").forEach((btn) => {
        const carte = btn.closest(".carte-livre");
        if (!carte) return;
        const estActivé = estFavori(carte.dataset.id);
        btn.classList.toggle("favori-actif", estActivé);
        btn.setAttribute("aria-pressed", estActivé ? "true" : "false");
    });
}

function filtrerCatalogue() {
    const recherche = document.querySelector("#search-titre");
    const filtreGenre = document.querySelector("#filter-genre");
    const cartes = document.querySelectorAll(".carte-livre");
    if (!cartes.length) return;

    const texte = recherche?.value.toLowerCase().trim() || "";
    const genre = filtreGenre?.value.toLowerCase() || "";

    cartes.forEach((carte) => {
        const titre = carte.dataset.titre.toLowerCase();
        const carteGenre = (carte.dataset.genre || "").toLowerCase();
        const afficheTitre = !texte || titre.includes(texte);
        const afficheGenre = !genre || carteGenre === genre;
        carte.style.display = afficheTitre && afficheGenre ? "" : "none";
    });
}

function attacherFiltresCatalogue() {
    const recherche = document.querySelector("#search-titre");
    const filtreGenre = document.querySelector("#filter-genre");
    if (recherche) recherche.addEventListener("input", filtrerCatalogue);
    if (filtreGenre) filtreGenre.addEventListener("change", filtrerCatalogue);
}

function ajouterAuPanier(livre) {
    // On s'assure que l'ID est traité de la même manière partout (String sans espaces)
    const idLivre = String(livre.id).trim();
    
    const ligne = panier.find((item) => String(item.id).trim() === idLivre);
    if (ligne) {
        ligne.qty = (ligne.qty || 1) + 1;
    } else {
        panier.push({ ...livre, id: idLivre, qty: 1 });
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
            ajouterAuPanier(livre);
            sauvegarderEtRafraichir();
        });
    });

    document.querySelectorAll(".btn-heart").forEach((bouton) => {
        bouton.addEventListener("click", () => {
            const carte = bouton.closest(".carte-livre");
            const livre = {
                id: carte.dataset.id,
                titre: carte.dataset.titre,
                auteur: carte.dataset.auteur,
                prix: carte.dataset.prix,
                image: carte.dataset.image
            };
            const ajouté = toggleFavori(livre);
            bouton.classList.toggle("favori-actif", ajouté);
            sauvegarderEtRafraichir();
        });
    });
}

majCompteurs();
attacherActionsCatalogue();
attacherFiltresCatalogue();
actualiserCoeurs();
sauvegarderEtRafraichir();
