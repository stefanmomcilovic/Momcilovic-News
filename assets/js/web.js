var d = new Date().toLocaleDateString("en-US", {
    weekday: 'long',
    year: 'numeric',
    month: 'long',
    day: 'numeric'
});
document.getElementById("date").innerHTML = d;

$(".search").click(function () {
    $(".inputs").toggleClass("hideInputs");
})
var y = new Date();
var n = y.getFullYear();
document.querySelector(".year").innerHTML = n;

//Get the button
var mybutton = document.getElementById("myBtn");

// When the user scrolls down 20px from the top of the document, show the button
window.onscroll = function () {
    scrollFunction()
};

function scrollFunction() {
    if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
        mybutton.style.display = "block";
    } else {
        mybutton.style.display = "none";
    }
}

// When the user clicks on the button, scroll to the top of the document
function topFunction() {
    document.body.scrollTop = 0;
    document.documentElement.scrollTop = 0;
}
//When Clicked open sidenavbar
function openNav() {
    document.getElementById("mySidebar").style.width = "100%";

}

function closeNav() {
    document.getElementById("mySidebar").style.width = "0";
}

//Show Search Bar when clicked sandwitch 
var sandwitch = document.querySelector(".sandwitch");

sandwitch.addEventListener("click", function () {
    var searchInput = $(".inputs");
    searchInput.toggleClass("hideInputs");

    if ($(window).width() < 768) {
        searchInput.css({
            "z-index": "99",
            "position": "fixed",
            "top": "0"
        })
    } else {
        searchInput.css({
            "z-index": "99",
            "position": "fixed"
        })
    }
});

var closeBtn = document.querySelector(".closebtn");
closeBtn.addEventListener("click", function () {
    var searchInput = $(".inputs");
    searchInput.css({
        "z-index": "1",
        "position": "static"
    })
    searchInput.toggleClass("hideInputs");
});

//Prevent Page To Show Firefox must send information that will repeat any action (such as a search or order confirmation) that was performed earlier.
if (window.history.replaceState) {
    window.history.replaceState(null, null, window.location.href);
}