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

function getMessage (key) {
    if (lang === "de") { // German messages
      switch (key) {
        case "email.invalid":
          return "Ungültige E-Mail-Adresse";
        case "contactform.sent":
          return "Vielen Dank für Deine Mitteilung!";
        case "contactform.error":
          return "Es tut mir leid, mein Mailserver antwortet gerade nicht. Bitte versuche es später nochmals!";
        default:
          return "Unbestimmbarer Fehler";
      }
    } else { // English messages
      switch (key) {
        case "email.invalid":
          return "Not a valid email address";
        case "contactform.sent":
          return "Thanks for your message!";
        case "contactform.error":
          return "Sorry, it seems that my mail server is not responding. Please try again later!";
        default:
          return "Undefined Error";
      }
    }
  }


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

// PGP select text

function selectText(containerid) {
    if (document.selection) {
        var range = document.body.createTextRange();
        range.moveToElementText(document.getElementById(containerid));
        range.select();
    } else if (window.getSelection()) {
        var range = document.createRange();
        range.selectNode(document.getElementById(containerid));
        window.getSelection().removeAllRanges();
        window.getSelection().addRange(range);
    }
}