document.getElementById("menuToggle").addEventListener("click", function () {
    var navigation = document.querySelector("nav");
    navigation.classList.toggle("active");
    if (navigation.classList.contains("active")) {
        this.textContent = "\u2716";
    } else {
        this.textContent = "\u2630";
    }
});
