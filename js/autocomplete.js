
// Partie Recherche Artiste
// CREATION AFFICHAGE BOX RESULTAT AJAX
var form_group = document.querySelector('#autocomplete .form-group');
var context_resultat = document.createElement('div');
context_resultat.className = "context_resultat";
form_group.appendChild(context_resultat);
// GET AJAX RESULTAT
var resultat = document.querySelector("#nom")
resultat.addEventListener("keyup", function () {
    if (resultat.value!= ""){
    var DATA = 'motcle=' + resultat.value;
    $.ajax({
        type: "POST",
        url: "/artiste/ajax",
        dataType: 'json',
        data: DATA,
        success: function AutocompleteArtiste(donnee) {
                context_resultat.innerHTML = '';
                donnee.forEach(function (item) { 
                    //affichage des resultat dans un tableau avec le lien vers la page de l'artiste recherché
                    context_resultat.innerHTML += "<a href='/artiste/" + item[1] + "' class='link_resultat'>" + item[0] + "</a>";
                })
        },
    })
    }
    else {
        context_resultat.innerHTML = '';
    } 
})
