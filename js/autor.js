function configuracao_inicial() {
    const urlParams = new URLSearchParams(window.location.search);
    const autorData = urlParams.get('autor');
    const xhttp = new XMLHttpRequest();
    const url = `https://openlibrary.org/authors/${autorData}.json`;
    xhttp.onload = function () {
        
        request = JSON.parse(this.responseText);
        console.log(request);

        document.querySelector("#nome_do_autor").textContent = request['name'];

        if(request.hasOwnProperty("bio")){
            document.body.querySelector("#biografia").textContent = request["bio"];
        }

        if(request.hasOwnProperty("birth_date")){
            document.body.querySelector("#nascido_em").textContent = request["birth_date"];
        }

        if(request.hasOwnProperty("photos")){
            document.body.querySelector("#foto_autor").src = `https://covers.openlibrary.org/a/id/${request['photos'][0]}-M.jpg`;
        }

    }

    xhttp.open("GET",url,true);
    xhttp.send();

    console.log("configuração da página do autor aplicada...");
}