(function($) {
		"use strict";

	$(document).ready(function() {
      //cart item remove code
    $('.cart-remove').on('click', function(){
        $(this).parent().parent().remove();
    });
      //cart item remove code ends


        /*  Bootstrap colorpicker js  */
        $('.cp').colorpicker();
        // Colorpicker Ends Here

        // IMAGE UPLOADING :)
        $(".img-upload").on( "change", function() {
          var imgpath = $(this).parent();
          var file = $(this);
          readURL(this,imgpath);
        });

        function readURL(input,imgpath) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
              imgpath.css('background', 'url('+e.target.result+')');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
        // IMAGE UPLOADING ENDS :)

        // GENERAL IMAGE UPLOADING :)
        $(".img-upload1").on( "change", function() {
          var imgpath = $(this).parent().prev().find('img');
          var file = $(this);
          readURL1(this,imgpath);
        });

        function readURL1(input,imgpath) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
              imgpath.attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
        // GENERAL IMAGE UPLOADING ENDS :)
      // Text Editor 
      //Simple Editor
      var elementArray = document.getElementsByClassName("trumboedit");
      for (var i = 0; i < elementArray.length; ++i) {
        $(elementArray[i]).trumbowyg({
          lang: trumbo_lang,
          btns: [
              ['viewHTML'],
              ['historyUndo', 'historyRedo'],
              ['formatting'],
              ['fontfamily','fontsize','foreColor', 'backColor'],
              ['strong', 'em', 'del'],
              ['superscript', 'subscript'],
              ['justifyLeft', 'justifyCenter', 'justifyRight', 'justifyFull'],
              ['unorderedList', 'orderedList'],
              ['horizontalRule'],
              ['removeformat'],
              ['link','insertImage','base64','noembed'],
              ['emoji'],
              ['table'],
          ]
      });
      }
      //Full Editor
      var elementArray = document.getElementsByClassName("trumboedit-p");
      for (var i = 0; i < elementArray.length; ++i) {
        $(elementArray[i]).trumbowyg({
          lang: trumbo_lang,
          btns: [
              ['viewHTML'],
              ['historyUndo', 'historyRedo'],
              ['formatting'],
              ['fontfamily','fontsize','foreColor', 'backColor'],
              ['strong', 'em', 'del'],
              ['superscript', 'subscript'],
              ['justifyLeft', 'justifyCenter', 'justifyRight', 'justifyFull'],
              ['unorderedList', 'orderedList'],
              ['horizontalRule'],
              ['removeformat'],
              ['link','insertImage','noembed', 'upload'],
              ['emoji'],
              ['table'],
          ],
          plugins: {
            upload: {
              serverPath: mainurl + '/trumbowyg-upload',
              headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              },
              fileFieldName: 'image',
              urlPropertyName: 'file'
          }
          }
      });
      }


        // Check Click :)
        $(".checkclick").on( "change", function() {
            if(this.checked){
             $(this).parent().parent().next().removeClass('showbox');
            }
            else{
             $(this).parent().parent().next().addClass('showbox');
            }

        });
        // Check Click Ends :)


        // Check Click1 :)
        $(".checkclick1").on( "change", function() {
            if(this.checked){
             $(this).parent().next().removeClass('showbox');
            }
            else{
             $(this).parent().next().addClass('showbox');
            }

        });
        // Check Click1 Ends :)

      //  Alert Close
      $("button.alert-close").on('click',function(){
        $(this).parent().hide();
      });

	});

// Drop Down Section Ends

})(jQuery);
