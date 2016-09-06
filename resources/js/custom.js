// Set up Galleria

Galleria.loadTheme("resources/vendor/galleria/themes/classic/galleria.classic.js");

$(".modal-photo").on("show.bs.modal", function (e) {
  var array = $(this).attr("id").split("-");
  var albumId = array[array.length-1];
  var json = "resources/json/photo/galleria/" + albumId + ".json";

  $.getJSON (json, function() {
    console.log ("success");
  })

  .done(function(data) {
    Galleria.run("#photos-"+albumId+" .galleria", { 
      dataSource: data,
      extend: function() {
        this.attachKeyboard({
          left: this.prev,
          right: this.next
        });
      } 
    });
    console.log ("second success");
  })
           
  .fail(function() {
    console.log ("error");
  });          
      
});