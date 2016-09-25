// Set up language
var lang = $('html').attr('lang');

$(".switch-language").click(function () {
  switch (lang) {
    case "en":
      document.cookie = "lang=de";
      break;
    default:
      document.cookie = "lang=en";
      break;
  }
});


// Set up Galleria

Galleria.loadTheme("resources/vendor/galleria/themes/classic/galleria.classic.js");

$(".modal-photo").on("show.bs.modal", function (e) {
  var array = $(this).attr("id").split("-");
  var albumId = array[array.length - 1];
  var json = "resources/json/photo/galleria/" + albumId + "-" + lang + ".json";

  $.getJSON(json, function () {
  })

    .done(function (data) {
      Galleria.run("#photos-" + albumId + " .galleria", {
        dataSource: data,
        extend: function () {
          this.attachKeyboard({
            left: this.prev,
            right: this.next
          });
        }
      });
    })

    .fail(function () {
      console.log("error");
    });

});