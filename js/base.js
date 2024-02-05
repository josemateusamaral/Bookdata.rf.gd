var sidebarStatus = false;


/* CONFIGURAÇÂO QUE È APLICADA NA ENTRADA DE TODAS AS PAGINAS */
function base_configuracao_inicial(){

    config_localStorage();

    //config nightmode
    if (localStorage.hasOwnProperty("night_mode-STATUS")) {
        if(localStorage.getItem("night_mode-STATUS") == "ativado"){
            toogle_nightMode();
        }
        else{
            toogle_nightMode();
            toogle_nightMode();
        }
    }
    else{
        toogle_nightMode();
        toogle_nightMode();
        localStorage.setItem("night_mode-STATUS", "desativado");
    }

    //config sidebar
    posicionar_Sidebar();
    addEventListener("resize", (event) => {
        posicionar_Sidebar();
    });
    
    //criar tags personalizadas
    document.body.querySelector("footer").innerHTML = CustomTag_Footer();
    let search_bar = document.body.querySelector("#search_bar");
    if(search_bar!=null){
        search_bar.innerHTML = CustomTag_SearchBar();
    }
    
    console.log('configuração base aplicada...');
}




function config_localStorage(){

    if(document.cookie == ''){
        let now = new Date();
        let futureDate = new Date(now.getFullYear() + 10, now.getMonth(), now.getDate()); // Define uma data no futuro (10 anos a partir de hoje)
        let cookieString = "favoritos=" + encodeURIComponent('') + "; expires=" + futureDate.toUTCString() + "; path=/";
        document.cookie = cookieString;
    }

}

function updateCookie(cookieName, newValue) {
    var now = new Date();
    var expirationDate = new Date(now.getFullYear() + 10, now.getMonth(), now.getDate()); // Calculate future expiration date (10 years from now)
  
    var updatedCookie = cookieName + "=" + encodeURIComponent(newValue) + "; expires=" + expirationDate.toUTCString() + "; path=/";
    document.cookie = updatedCookie;
}





function posicionar_Sidebar(){
    document.getElementById("sidebar").style.top = document.getElementById("navbar").scrollHeight + "px";
    document.getElementById("sidebar").style.right = 0 + "px";
}



/* ATIVAR E DESATIVAR SIDEBAR PARA APARELHOS MOVEIS */
function toogleSideBar(){
    let bar = document.getElementById('sidebar');
    if(sidebarStatus){
        bar.style.visibility = 'hidden';
        sidebarStatus = false;
    }
    else{
        bar.style.visibility = 'visible';
        sidebarStatus = true;
    }
}



/* ATIVAR E DESATIVAR O MODO DE AUTO CONTRASTE */
function toogle_nightMode(){
    document.body.classList.toggle('night_mode');
    document.body.querySelector("nav").classList.toggle('navbar-dark');
    document.body.querySelector("nav").classList.toggle('bg-dark');
    document.body.querySelector("#sidebar").classList.toggle('bg-dark');
    let botoesSideBar = document.body.getElementsByClassName("botoesSidebar");
    if(document.body.classList.contains("night_mode")){
        localStorage.setItem("night_mode-STATUS", "ativado");
        document.getElementById("n_mode").innerText = "\u{263E}";
        document.getElementById("n_mode_S").innerText = "\u{263E}";
        for( let botao = 0 ; botao < botoesSideBar.length ; botao++ ){
            botoesSideBar[botao].style.color = "#fff";
        }
    }
    else{
        localStorage.setItem("night_mode-STATUS", "desativado");
        document.getElementById("n_mode").innerText = "\u{2600}";
        document.getElementById("n_mode_S").innerText = "\u{2600}";
        for( let botao = 0 ; botao < botoesSideBar.length ; botao++ ){
            botoesSideBar[botao].style.color = "#000";
        }
    }

}



/* TAGS PERSONALIZADAS */
function CustomTag_Item(titulo,capa,book){
    return `
    <div class="item">
        <div class="default_img">
            <img
                class = "cover" 
                src = "https://covers.openlibrary.org/b/id/${capa}.jpg" 
                onclick = 'window.location.assign("book.php?l=${book}")'
            >
        </div>
        <p class="titulo">${titulo}</p>
    </div>`;
}

function CustomTag_Footer(){
    return `
    <hr>
    <p>
        Website made by José Mateus Amaral
    </p>
    <a
    `;
}

function CustomTag_SearchBar(){
    return`
    <div id="search_container">
        <div id="inputs">
            <div id="banner">
                <h1 id="titulo_site" class="align-middle">Book_DATA</h1>
            </div>
            <div id="pesquisa-container">
                <input type="text" id="pesquisa" placeholder="search a book" autofocus>
                <select id="tipo_pesquisa">
                    <option value="title">title</option>
                    <option value="author">author</option>
                </select>
                <input type="button" value="  search  " onclick="pesquisar()" id="search">
            </div>
        </div>
    </div>
    `;
}
