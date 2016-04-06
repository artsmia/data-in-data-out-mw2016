$(function(){
  'use strict';
   // jQuery methods go here...

   $("#search").change(function(){
     $( ".wrapper" ).empty();
     var term = $( "#search" ).val();
     var ES_url = '/search.php?q='
     $.getJSON(ES_url + term, function(data) {

         $.each(data.hits, function (i, ob) {
             $.each(ob, function (ind, obj) {

               var img_valid = obj._source.image;

               if (img_valid === "valid"){
                 var image_url = "http://"+obj._id%7+".api.artsmia.org/"+obj._id+".jpg";
               } else {
                 var image_url = "images/icons/placeholder--medium.png";
               }

                 var json_display = JSON.stringify(obj, null, 2);

                   $(".wrapper").append("\
                    <div class='json_wrapper g--medium-1 g-wide--2'>\
                       <pre style='font-size: 77%; line-height: 1em;'><code class='code'>\
		         "+ json_display +"\
		       </code></pre>\
                     </div>\
                     <div class='result_wrapper g--medium-1 g-wide--2 g--last'>\
                       <div class='result'>\
                         <div class='result-image'>\
                          <img src="+image_url+" alt='Object image'>\
                          <a href='#' id="+obj._id+" class='favorite'><i class='fa fa-heart'></i></a>\
                         </div>\
                         <div class='result-tombstone'>\
                          <h4>"+ obj._source.title +", "+ obj._source.dated +"</h4>\
                          <p>"+ obj._source.artist +", "+ obj._source.life_date +"</p>\
                          <p>"+ obj._source.text +"<br/> <sub>"+ obj._source.creditline +"</sub></p>\
                         </div>\
                       </div>\
                   </div>");
             });
         });
     });

    });

    $(document).on('click', '.favorite', function(){
      event.preventDefault();
      var object = $(this).attr("id");
      $.ajax({
         type: "POST",
         url: "redis.php?page=favorite",
         data: {"object" : object},
         success: function(response){
           $( ".notify" ).fadeIn( "slow").delay(3000).fadeOut();;
         }
       });
    });
});
