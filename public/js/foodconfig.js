// Global site tag (gtag.js) - Google Analytics
window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-E8H0MZC78C');

var prevScrollpos = window.pageYOffset;
window.onscroll = function() {
    var currentScrollPos = window.pageYOffset;
    if (prevScrollpos > currentScrollPos) {
        document.getElementById("navbar").style.top = "0";
    } else {
        document.getElementById("navbar").style.top = "-50px";
    }
    prevScrollpos = currentScrollPos;
}

window.addEventListener("load", function(){
    window.cookieconsent.initialise({
        "palette": {
            "popup": {
                "background": "#2e2e2e",
                "text": "#9b9b9b"
            },
            "button": {
                "background": "#038f3e",
                "text": "#ffffff"
            }
        },
        "theme": "classic",
        "position": "bottom-left",
        "content": {
            "message": "Ta strona korzysta z ciasteczek (cookies) aby zapewnić najwyższy komfort użytkowania strony!",
            "dismiss": "Rozumiem!",
            "link": "Więcej informacji",
            "href": "/policy"
        }
    })});