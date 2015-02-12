var button = {
    sayHi: function () {
        document.querySelectorAll(".button")[0].addEventListener("click", function () {
            alert("Hi!");
        });
    }
};

module.exports = button;