// on récupère tous les faux boutons
const deleteButtons = document.querySelectorAll('.falseDelete');

// Pour chaque bouton récupérer
deleteButtons.forEach((deleteButton => {
        // On créer un évènement (ici se sera un clique)
        deleteButton.addEventListener('click', () =>{
                // lorsque l'on clique sûr les faux boutons, le vrai bouton de suppression apparaitra dans une fenètre popup
                // qui été jusque là caché avec un "display: none"
                const popup = deleteButton.nextElementSibling;
                popup.style.display = 'block';
        })
}
))