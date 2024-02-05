var listaLivros = [];

function configuracao_inicial() {
    
    //variaveis da pesquisa
    const urlParams = new URLSearchParams(window.location.search);
    const livroPesquisa = urlParams.get('s');
    const tipo_de_pesquisa = urlParams.get('t');

    //configurar para pesquisar usando o enter
    const input = document.getElementById("pesquisa");
    input.addEventListener("keyup", (event) => {
        if (event.key === "Enter") {
            pesquisar();
        }
    });
    
    //configurar os inputs para ficarem de acordo com as variaveis da pesquisa
    document.getElementById('pesquisa').value = livroPesquisa.replace('%20',' ')
    document.body.querySelector("#tipo_pesquisa").value = tipo_de_pesquisa;
    
    renderizar_pesquisa(livroPesquisa,tipo_de_pesquisa);
}

function pesquisar(){
    
    //coletar o texto da pesquisa
    pesquisa_string = document.getElementById("pesquisa").value;
    let pesquisa = "";
    for (let i = 0; i < pesquisa_string.length; i++) {
        if (i != 0) {
            pesquisa += "+";   
        }
        pesquisa += pesquisa_string[i];
    }
    
    //coletar o tipo da pesquisa
    let tipo = document.body.querySelector("#tipo_pesquisa").value;
    
    //pesquisar
    const pesquisaRow = `search.php?s=${pesquisa_string}&t=${tipo}`;
    window.location.href = pesquisaRow;
}

function renderizar_pesquisa(pesquisa_string,tipo) {

    //zerar tela
    document.getElementById("lista").innerHTML = "";
    document.getElementById("loading").style.display = "initial";
    const xhttp = new XMLHttpRequest();

    //desenhar livros
    xhttp.onload = function () {

        //coletar os dados da pesquisa
        requestData = JSON.parse(this.responseText);
        console.log(requestData);
        listaLivros = requestData['docs'];
        
        if(listaLivros.length){

            //criar o html dos livros da lista se ela não estiver vazia
            for (let i = 0; i < listaLivros.length; i++) {

                console.log(listaLivros[i]);

                //dados do livro
                const book = listaLivros[i]['key'].split('/')[2];
                const titulo = listaLivros[i]['title'];
                const capa = listaLivros[i]['cover_i'];
                console.log(capa);
                document.getElementById('lista').innerHTML += CustomTag_Item(titulo,capa,book);
            }

        }
        else{

            //mostrar mensagem deque a pesquisa não teve resultado pois a lista de livros esta vazia
            document.getElementById('lista').innerHTML += `
              <div class="sem_resultados">
                  <p>
                      Sua pesquisa não encontrou nenhum resultado :(<br><br>DICA: Tente procurar por um nome mais específico
                  </p>
              </div>
            `;
        }

        //remover o gif de carregamento
        document.getElementById("loading").style.display = "none";
    }

    xhttp.open("GET", `https://openlibrary.org/search.json?${tipo}=${pesquisa_string}`, true);
    xhttp.send();

    console.log("configuração da página de pesquisa aplicada...");
}
