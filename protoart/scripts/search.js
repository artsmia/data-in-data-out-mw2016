$(function(){
  'use strict';
   // jQuery methods go here...

   $("#search").change(function(){
     $( ".wrapper" ).empty();
     var term = $( "#search" ).val();
     $.getJSON("http://search.artsmia.org/" + term, function(data) {

         $.each(data.hits, function (i, ob) {
             $.each(ob, function (ind, obj) {
                 var image_url = "http://1.api.artsmia.org/full/"+obj._id+".jpg";
                 var json_display = JSON.stringify(obj);
                   $(".wrapper").append("\
                    <div class='json_wrapper g--medium-1 g-wide--2'>\
                       <div class='code'>\
                        <p>"+ json_display +"</p>\
                       </div>\
                     </div>\
                     <div class='result_wrapper g--medium-1 g-wide--2 g--last'>\
                       <div class='result'>\
                         <div class='result-image'>\
                          <img src="+image_url+" alt='Object image'>\
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


    });
