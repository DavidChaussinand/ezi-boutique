document.addEventListener('DOMContentLoaded', function() {
    // Stockez les informations de chaque ville dans un objet
    const villeInfo = {
        'valence': {
            'nom': 'Valence',
            'adresses': [
                '163 rue chateauvert',
                '163 rue victor hugo',
                '163 rue des alpes',
                'la fnac',
                'galerie marchande:'
            ]
        },
        'montelimar': {
            'nom': 'Montelimar',
            'adresses': [
                'Monoprix',
                '100 rue victor hugo',
                '5 rue de la mairie'
            ]
        }
    };

    // Fonction pour créer et afficher les informations de la ville
    function afficherInfoVille(ville) {
        // Créez un conteneur pour les informations
        const container = document.createElement('div');
        container.className = 'container_lieux_vente_carte_description';
    
        // Ajoutez le nom de la ville
        const h4 = document.createElement('h4');
        h4.className = 'ville-titre';
        h4.textContent = villeInfo[ville].nom;
        container.appendChild(h4);
    
        // Créez une liste pour les adresses
        const ul = document.createElement('ul');
        ul.className = 'ville-adresses'; // Ajoutez une classe pour le style si nécessaire
    
        // Ajoutez les adresses sous forme de liste
        villeInfo[ville].adresses.forEach(adresse => {
            const li = document.createElement('li');
            li.className = 'ville-adresse'; // Ajoutez une classe pour le style si nécessaire
            li.textContent = adresse;
            ul.appendChild(li);
        });
    
        // Ajoutez la liste au conteneur d'informations
        container.appendChild(ul);
    
        // Sélectionnez l'article et effacez les contenus précédents
        const article = document.querySelector('.container_lieux_vente_carte');
        const previousDescriptions = document.querySelectorAll('.container_lieux_vente_carte_description');
        previousDescriptions.forEach(desc => desc.remove());
    
        // Ajoutez le nouveau conteneur d'informations
        article.appendChild(container);
    }
    

    // Ajoutez un écouteur d'événement à chaque ville
    const villes = document.querySelectorAll('.ville');
    villes.forEach(ville => {
        ville.addEventListener('click', function() {
            afficherInfoVille(ville.id);
        });
    });
});

