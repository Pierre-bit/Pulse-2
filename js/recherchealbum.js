// Partie Recherche Album
// CREATION AFFICHAGE BOX RESULTAT AJAX
var form_group = document.querySelector('#recherche .form-group');
var context_resultat = document.createElement('div');
context_resultat.className = "context_resultat";
form_group.appendChild(context_resultat);

// GET AJAX RESULTAT
var resultat = document.querySelector("#titre")
resultat.addEventListener("keyup", function () {
    // console.log(resultat.value)
    if (resultat.value != "") {
        var DATA = 'motcle=' + resultat.value;
        $.ajax({
            type: "POST",
            url: "/albums/recherche",
            dataType: 'json',
            data: DATA,

            success: function RechercheAlbum(donnee) {
                // reponse($.map(donnee, function () {
                context_resultat_album.innerHTML = '';
                donnee.forEach(function (item) {
                    context_resultat_album.innerHTML += "<a href='/albums/" + item[1] + "' class='link_resultat'>" + item[0] + item[2] + "</a>";
                })
                // }));
            }
        })
        // minLength: 1;
    }
    else {
        alert("Pas de resultat");
    }
})