$(document).ready(function () {
    /*
    Première étape je définis d'abord toutes les variables dont j'ai besoin
    pour mon lecteur.
    */

        const playlist =document.getElementById("playlist")
        const tbPlaylist = JSON.parse(playlist.dataset.playdata);
        console.dir(tbPlaylist) 



    // tableau d'informations pour chaque fichier audio 

    // definition des étapes de volumes + et - (de 1 à 0)
    let stepAudio = 0.05;
    // indice de lecture des fichiers audio en fonction du tableau tbPlaylist 
    let currentTrack = 0;
    // création du fichier audio qui va être lu (toujours en fonction de l'indice currentTrack)
    let audioObj = new Audio(urlAudio + tbPlaylist[currentTrack].mp3);
    // nombre total d'entrées dans la playlist
    let totalTrack = tbPlaylist.length;
    // une variable playPause va définir si notre lecteur li un fichier 
    // ou pas - false = pause, true = play. A l'ouverture de la page la lecture est en pause (false)
    let playPause = false;

    /*
    Seconde étape je définis une fonction pour chaque action du lecteur.
    Elles sont toutes volontairement séparées pour au besoin être réutilisées
    et pour être plus facilement debuggables
    */

    // une fonction lecture
    function playTrack() {
        console.log("lecture");
        // lecture du fichier audio
        audioObj.play();
        // affichage des information de temps du fichier audio.
        displayTime();
        //getTags()
    }
    // une fonction de pause
    function pauseTrack() {
        console.log("pause");
        // pause du fichier audio
        audioObj.pause()
    }
    // une fonction piste suivante
    function nextTrack() {
        console.log("Next track");
        // stopper la music en cours
        pauseTrack();
        // Determiner ou je suis dans mon tableau.
        // Si currentTrack est à la fin de mon tableau tbPlaylist
        if (currentTrack === (totalTrack - 1)) {
            // je repositionne currentTrack au début de mon tableau
            currentTrack = 0;
            // ouvrir le fichier audio correspondant
            audioObj = new Audio(urlAudio + tbPlaylist[currentTrack].mp3);
            // ou ailleur    
        } else {
            // avancer d'une piste dans mon tableau
            currentTrack++;
            // ouvrir le fichier audio correspondant
            audioObj = new Audio(urlAudio + tbPlaylist[currentTrack].mp3);
        }
        // changer la cover correspondant à l'actuel fichier audio
        displayCover();
        // verifier que le bouton Play/Pause soit bien en mode lecture
        displayPlay();
        // lire le prochain fichier audio
        playTrack();
    }
    // une fonction piste précédante
    function prevTrack() {
        console.log("Previous track");
        // stopper la music en cours
        pauseTrack();
        // determiner ou je suis dans mon tableau
        // Si currentTrack est au début de mon tableau tbPlaylist
        if (currentTrack === 0) {
            // je repositionne currentTrack à la fin de mon tableau
            currentTrack = totalTrack - 1;
            // ouvrir le fichier audio correspondant
            audioObj = new Audio(urlAudio + tbPlaylist[currentTrack].mp3);
            // ou ailleur
        } else {
            // reculer d'une piste dans mon tableau
            currentTrack--;
            // ouvrir le fichier audio correspondant
            audioObj = new Audio(urlAudio + tbPlaylist[currentTrack].mp3);
        }
        // changer la cover correspondant à l'actuel fichier audio
        displayCover();
        // verifier que le bouton Play/Pause soit bien en mode lecture
        displayPlay();
        // lire le prochain fichier audio
        playTrack();
    }
    let minutes;
    // pour l'affichage du temp écoulé j'ai besoin d'une fonction particulière qui va me convertir
    // les secondes en format "minutes . secondes" 
    // stackoverflow : https://stackoverflow.com/questions/4605342/how-to-format-html5-audios-currenttime-property-with-javascript/4605470
    function formatTime(seconds) {
        minutes = Math.floor(seconds / 60);
        //minutes = (minutes >= 10) ? minutes : "0" + minutes;
        if(minutes >= 10){
            minutes = minutes
        }else{
            minutes = "0"+minutes
        }
        seconds = Math.floor(seconds % 60);
        seconds = (seconds >= 10) ? seconds : "0" + seconds;
        return minutes + ":" + seconds;
    }
    // fonction d'affichage du temps total du fichier audio et de son avancement
    function displayTime() {
        // création d'une boucle d'action toutes les secondes (1000)
        console.log("temps total", audioObj.duration);
            console.log("secondes ecoulées", audioObj.currentTime);
        setInterval(() => {
            
            // je determine si je suis à la fin de mon fichier audio
            if (audioObj.currentTime >= audioObj.duration) {
                // si c'est le cas je passe au fichier suivant
                nextTrack();
            }
            // affichage de la progression
            //Math.round(num * 100) / 100 sert à ne garder que 2 chiffres après la virgule pour un float.
            $("#time").html(
                formatTime(audioObj.currentTime) +
                " / " +
                formatTime(audioObj.duration)
            );
        }, 1000)
    }
    // fonction d'affichage de l'image liée au titre audio
    function displayCover() {
        $("#cover img").attr("src", urlImgCover + tbPlaylist[currentTrack].id +".jpg");
    }
    //fonction de gestion de l'affichage du bouton pause quand la lecture est en cours
    function displayPlay() {
        playPause = true;
        $("#playPause img").attr("src", assetImg+"pause-circle-regular.svg");
    }
    //fonction de gestion de l'affichage du bouton play quand la lecture est arretée
    function displayPause() {
        playPause = false;
        $("#playPause img").attr("src", assetImg+"play-circle-solid.svg");
    }
    //fonctions de gestion du volume
    function volumeUp() {
        console.log(audioObj.volume);
        if (audioObj.volume + stepAudio < 1) {
            audioObj.volume += stepAudio;
        } else {
            audioObj.volume = 1;
        }
    }
    function volumeDown() {
        console.log(audioObj.volume);
        if (audioObj.volume - stepAudio > 0) {
            audioObj.volume -= stepAudio;
        }
        else {
            audioObj.volume = 0;
        }
    }
    // bonus track
    function getTags() {
        var reader = new FileReader();

        reader.onload = function (e) {
            var dv = new jDataView(this.result);

            // "TAG" starts at byte -128 from EOF.
            // See http://en.wikipedia.org/wiki/ID3
            if (dv.getString(3, dv.byteLength - 128) == 'TAG') {
                var title = dv.getString(30, dv.tell());
                console.log(title);
                var artist = dv.getString(30, dv.tell());
                console.log(artist);
                var album = dv.getString(30, dv.tell());
                console.log(album);
                var year = dv.getString(4, dv.tell());
                console.log(year);
            } else {
                // no ID3v1 data found.
            }
        };

        reader.readAsArrayBuffer(audioObj);
    }
    /*
    Derniere étape la gestion des action de l'utilisateur
    */
    // event pour l'action ...
    $("#playPause").click(() => {
        // !boolean signifie false
        if (!playPause) {
            // sur le bouton play
            displayPlay();
            playTrack();
        } else {
            //  sur le bouton pause
            displayPause();
            pauseTrack();

        }

    })
    //event pour le bouton précédent
    $("#prev").click(() => {
        prevTrack()
    })
    //event pour le pouton suivant
    $("#next").click(() => {
        nextTrack()
    })
    //event pour baisser le volume
    $("#down").click(() => {
        volumeDown();
    })
    //event pour augmenter le volume
    $("#up").click(() => {
        volumeUp();
    })
    // affichage des éléments de depart
    $("#time").html(
        formatTime(audioObj.currentTime) +
        " / " +
        "00:00"
    );
    $("#cover img").attr("src", urlImgCover + tbPlaylist[currentTrack].id +".jpg")






});


