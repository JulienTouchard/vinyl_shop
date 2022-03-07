const rechercheTitle =  document.querySelector("#rechercheTitle")
const resultTitle =  document.querySelector("#resultTitle") 

rechercheTitle.addEventListener("keyup",()=>{
    if (rechercheTitle.value !== "") {
        resultTitle.style.padding = ".5rem";
        console.dir(rechercheTitle.value);
        fetch(ajaxRoute,
        {
            method:"POST",
            headers: {'Content-Type':'application/x-www-form-urlencoded'},
            body:"rechercheTitle=" + rechercheTitle.value
        }
        )
            .then((resultat) => {
                return resultat.json()
            })
            .then((retourJson) => {
                let contenu = "";
                // supprimer le dernier caractère de show
                let url = show.slice(0,-1)
                console.log(url)
                for (value in retourJson) {
                    contenu += "<a href='"+url+retourJson[value].id+"'>"+retourJson[value].title + " - " + retourJson[value].artiste + "</a><br>";
                }
                if (retourJson.length === 0) {
                   contenu = "Aucun resultat."; 
                }
                resultTitle.innerHTML = contenu;
            })
    } else {
        resultTitle.innerHTML = "";
        resultTitle.style.padding = 0;
    }
})