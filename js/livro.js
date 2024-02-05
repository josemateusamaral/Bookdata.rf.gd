var livroBD;
var capa;

function configuracao_inicial() {

    //document.getElementById("favorito").style.fontStyle = '1vw';
    //document.getElementById("favorito").innerText = '\u2605';

    const urlParams = new URLSearchParams(window.location.search);
    const livroPesquisa = urlParams.get('l');
    const xhttp = new XMLHttpRequest();
    const url = `https://openlibrary.org/works/${livroPesquisa}.json`;

    xhttp.onload = function () {
        
        request = JSON.parse(this.responseText);
        //console.log(request);

        //configurar descrição
        if( request.hasOwnProperty('description') ){
            if( typeof request['description'] === 'string' ){
                document.getElementById("descricao").innerText = request['description'];
            }
            else{
                document.getElementById("descricao").innerText = request['description']['value'];
            }
        }

        //configurar capa do livro
        if( request.hasOwnProperty('covers') ){
            capa = request['covers'][0];
            document.getElementById("cover").src = `https://covers.openlibrary.org/b/id/${capa}.jpg`;
        }
        else{
            document.getElementById("cover").src = "https://islandpress.org/sites/default/files/default_book_cover_2015.jpg";
        }


        //configurar titulo do livro
        document.getElementById("titulo_livro").innerText += request['title'];
        

        //configurar data de publicação
        if( request.hasOwnProperty("data_de_publicacao") ){
            document.getElementById("data_de_publicacao").innerText = request['first_publish_date'];
        }
        
        //configurar assuntos do livro
        if( request.hasOwnProperty("subjects") ){
            if( request['subjects'].length > 0){
                document.getElementById('assuntos_abordados').innerText = "";
                for( let assunto = 0 ; assunto < request['subjects'].length ; assunto++ ){
                    document.getElementById('assuntos_abordados').innerText += request['subjects'][assunto] + '\n';   
                }
            }
        }
        
        //configurar nome do autor
        if( request.hasOwnProperty("authors") ){
            document.getElementById('autor').innerText = "";
            for( let i = 0 ; i < request['authors'].length ; i++ ){
                const xhttpAutor = new XMLHttpRequest();
                xhttpAutor.onload = function () {
                    let autorData = JSON.parse(this.responseText);
                    const autor = request['authors'][i]['author']['key'].split('/')[2]
                    document.getElementById('autor').innerHTML += `
                    <a id="link_autor" href="author.php?autor=${autor}">${autorData["name"]}</a>
                    <br>`;   
                }
                xhttpAutor.open("GET",`https://openlibrary.org${request['authors'][i]['author']['key']}.json`,true);
                xhttpAutor.send();
            }
            if(request['authors'].length > 1){
                document.getElementById('h2_autor').innerText = 'Authors';
            }
        }


        livroBD = {
            "id":livroPesquisa,
            "titulo":request['title'],
            "capa":capa,
            "key":request['key']
        };

        //console.log(`,{\n"id":"${}",\n"titulo":"${request['title']}",\n"capa":"${request['covers'][0]}"\n"key":"${request['key']}"\n}`);
        console.log("configuração da página do livro aplicada...");
        //updateFavorito();
        let fav = livroBD['id'] + '$' + livroBD['titulo'] + '$' + livroBD['capa'] + '$' + livroBD['key'];
        document.getElementById('favoriteData').value = fav;

    }

    xhttp.open("GET",url,true);
    xhttp.send();

    
}







/*
function isFavorite(){
    let favoritos = document.cookie.split('=')[1].split('Spartak007');
    console.log(favoritos);
    var isIn = false;
    for( let i = 0 ; i < favoritos.length ; i++ ){

        let favorito = favoritos[i].split('Spartak_001');
        console.log(favorito);

        if(favorito[0] == livroBD.id ){
            isIn = true;
            break;
        }
    }
    return isIn;
}


function toogleFavorito(){

    let bd = document.cookie.split('=')[1];
    var favoritos = bd.split('Spartak007');

    console.log(favoritos);
    
    var novoFavoritos = '';
    if(isFavorite()){
        for( let i = 0 ; i < favoritos.length ; i++ ){

            let favorito = favoritos[i].split('Spartak007');

            if(favorito[0] != livroBD.id ){
                if(novoFavoritos != ''){
                    novoFavoritos += 'Spartak007';
                }
                novoFavoritos += favoritos[i];
            }
        }
    } 
    else{
        let fav = livroBD['id'] + 'Spartak_001' + livroBD['title'] + 'Spartak_001' + livroBD['capa'] + 'Spartak_001' + livroBD['key'];
        if(bd == ''){
            novoFavoritos = fav;
        }
        else{
            novoFavoritos += 'Spartak007' + fav;
        }
    }

    updateCookie('favoritos',novoFavoritos);
    updateFavorito();

}

function updateFavorito(){

    document.getElementById("favorito").style.fontStyle = '1vw';
    document.getElementById("favorito").innerText = '\u2605';

    if(isFavorite()){
        document.getElementById("favorito").style.color = "#444444";
    }else{
        document.getElementById("favorito").style.color = "#00FF00";
    }
    
}
*/