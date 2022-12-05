const form = document.querySelector('#shortenForm');
const shortenCard = document.querySelector('#shortenCard');
const inputUrl = document.querySelector('#url');
const btnShortenUrl = document.querySelector('#btnShortenUrl');

const URL_SHORTEN ='/ajax/shorten';
const errorMessages = {
    'INVALID_ARG_URL': "Impossible de raccourcir ce lien. Ce n'est pas une URL  valide",
    'MISSING_ARG_URL': "Veuillez fournir une URL valide !",
}

form.addEventListener( 'submit', function ( e ) {
    e.preventDefault();

    fetch(UR_SHORTEN, {
        method : 'POST',
        body    : FormDate(e.target) 
    })
    .then(response => response.json())
    .then(handleData)   
})

const handleData = function(data) {
    if(data.statusCode >=400){
        return handleError(data);
    }
    inputUrl.value = data.link; //lien que l'on recois
    btnShortenUrl.innerText = "Copier";
    btnShortenUrl.addEventListener('click', function(e) {
        e.preventDefault();
        inputUrl.Select();
        document.execCommand('copy');
        this.innerText="Réduire l'URL";
    }, {once: true});//pour que l'event ne se produit que une seule fois 
}
const handleError = function (data) {
    const alert = document.createDocumentFragment('div');
    alert.classList.add('alert', 'alert-danger','nt-2');
    alert.innerText = errorMessages[data.statusText];

    shortenCard.after(alert);
};