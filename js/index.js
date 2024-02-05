var listaLivros = [];

function configuracao_inicial() {

    //configurar para pesquisar usando o enter
    const input = document.getElementById("pesquisa");
    input.addEventListener("keyup", (event) => {
        if (event.key === "Enter") {
            pesquisar();
        }
    });
    console.log("configuração da página inicial aplicada...");
}


function pesquisar() {

    //coletar dados da pesquisa
    let pesquisa = document.getElementById("pesquisa").value;
    let tipo_de_pesquisa = document.body.querySelector("#tipo_pesquisa").value;

    //pesquisar
    let pesquisaRow = `search.php?s=${pesquisa}&t=${tipo_de_pesquisa}`;
    window.location.href = pesquisaRow;
}
