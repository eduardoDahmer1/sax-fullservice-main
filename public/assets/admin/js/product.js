(function($) {
		"use strict";
		
	$(document).ready(function() {

        $("#uploadgallery_material").prop("required", false);
        $("#uploadgallery_color").prop("required", false);

        if($("#check3").is(":checked")){
            $("#uploadgallery_material").prop("required", false);
            $("#size-check").parent().hide();
            $("#check_material").parent().hide();
        }
        if($("#size-check").is(":checked")){
            $("#check3").parent().hide();
            $("#check_material").parent().hide();
        }
        if($("#check_material").is(":checked")){
            $("#uploadgallery_color").prop("required", false);
            $("#check3").parent().hide();
            $("#size-check").parent().hide();
        }
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


        // Product Measure :)

        $("#product_measure").on( "change" ,function() {
            var val = $(this).val();
            $('#measurement').val(val);
            if(val == "Custom")
            {
            $('#measurement').val('');
              $('#measure').show();
            }
            else{
              $('#measure').hide();      
            }
        });

        // Product Measure Ends :)

	});

// TAGIT

          $("#metatags").tagit({
          fieldName: "meta_tag[]",
          allowSpaces: true 
          });

          $("#tags").tagit({
          fieldName: "tags[]",
          allowSpaces: true 
        });
// TAGIT ENDS


// Remove White Space


  function isEmpty(el){
      return !$.trim(el.html())
  }


// Remove White Space Ends

// Size Section

$("#size-btn").on('click', function(){

    $("#size-section").append(''+
                            '<div class="size-area">'+
                                '<span class="remove size-remove"><i class="fas fa-times"></i></span>'+
                                    '<div  class="row">'+
                                        '<div class="col-md-4 col-sm-6">'+
                                            '<label>'+
                                            'Tamanho :'+
                                                '<span>(eg. S,M,L,XL,XXL,3XL,4XL)</span>'+
                                            '</label>'+
                                            '<input type="text" name="size[]" class="input-field" placeholder="Tamanho">'+
                                        '</div>'+
                                        '<div class="col-md-4 col-sm-6">'+
                                            '<label>'+
                                            'Estoque :'+
                                            '<span>(Estoque deste tamanho)</span>'+
                                            '</label>'+
                                            '<input type="number" name="size_qty[]" class="input-field" placeholder="Estoque" value="1" min="1">'+
                                        '</div>'+
                                        '<div class="col-md-4 col-sm-6">'+
                                            '<label>'+
                                            'Preço Adicional:'+
                                            '<span>(Este preço será adicionado ao preço do produto)</span>'+
                                            '</label>'+
                                            '<input type="number" step="0.01" name="size_price[]" class="input-field" placeholder="Preço Adicional" value="0" min="0">'+
                                        '</div>'+
                                    '</div>'+
                                '</div>'
                            +'');

});

$(document).on('click','.size-remove', function(){

    $(this.parentNode).remove();
    if (isEmpty($('#size-section'))) {

    $("#size-section").append(''+
                            '<div class="size-area">'+
                                '<span class="remove size-remove"><i class="fas fa-times"></i></span>'+
                                    '<div  class="row">'+
                                        '<div class="col-md-4 col-sm-6">'+
                                            '<label>'+
                                            'Tamanho :'+
                                                '<span>(eg. S,M,L,XL,XXL,3XL,4XL)</span>'+
                                            '</label>'+
                                            '<input type="text" name="size[]" class="input-field" placeholder="Tamanho">'+
                                        '</div>'+
                                        '<div class="col-md-4 col-sm-6">'+
                                            '<label>'+
                                            'Size Qty :'+
                                            '<span>(Estoque deste tamanho)</span>'+
                                            '</label>'+
                                            '<input type="number" name="size_qty[]" class="input-field" placeholder="Estoque" value="1" min="1">'+
                                        '</div>'+
                                        '<div class="col-md-4 col-sm-6">'+
                                            '<label>'+
                                            'Preço Adicional :'+
                                            '<span>(Este preço será adicionado ao preço do produto)</span>'+
                                            '</label>'+
                                            '<input type="number" step="0.01" name="size_price[]" class="input-field" placeholder="Preço Adicional" value="0" min="0">'+
                                        '</div>'+
                                    '</div>'+
                                '</div>'
                            +'');


    }

});

$("#size-check").change(function(){
    $("#check3").parent().toggle();
    $("#check_material").parent().toggle();
});

// Size Section Ends

$("#check_material").change(function(){
    if($(this).is(":checked")){
        $("#uploadgallery_material").prop("required", true);
    } else $("#uploadgallery_material").prop("required", false);
    $("#size-check").parent().toggle();
    $("#check3").parent().toggle();
});

// Color Section

$("#check3").change(function(){
    $("#size-check").parent().toggle();
    $("#check_material").parent().toggle();
});


$("#color-btn").on('click', function(){
    $("#color-section").append(''+
            '<div class="row">'+
                '<div class="col-md-2">'+
                    '<div class="select-input-color">'+
                        '<div class="color-area">'+
                            '<div class="input-group colorpicker-component cp">'+
                                '<input type="text" name="color[]" value="#000000"'+
                                    'class="input-field cp" />'+
                                '<span class="input-group-addon"><i></i></span>'+
                            '</div>'+
                        '</div>'+
                    '</div>'+
                '</div>'+
                '<div class="col-md-3">'+
                    '<input type="number" name="color_qty[]" class="input-field"'+
                        'placeholder="" value="1" min="1">'+
                '</div>'+
                '<div class="col-md-3">'+
                    '<input type="number" step="0.01" name="color_price[]" class="input-field"'+
                        'placeholder="" value="0" min="0">'+
                '</div>'+
                '<div class="col-md-1">'+
                    '<button type="button" class="btn btn-danger text-white color-remove"><i class="fa fa-times"></i></button>'+
                '</div>'+
            '</div>');
    $('.cp').colorpicker();
});


$(document).on('click','.color-remove', function(){
    $(this.parentNode).parent().remove();
    if (isEmpty($('#color-section'))) {

        $("#color-section").append(''+
            '<div class="row">'+
                '<div class="col-md-3">'+
                    '<div class="select-input-color">'+
                        '<div class="color-area">'+
                            '<div class="input-group colorpicker-component cp">'+
                                '<input type="text" name="color[]" value="#000000"'+
                                    'class="input-field cp" />'+
                                '<span class="input-group-addon"><i></i></span>'+
                            '</div>'+
                        '</div>'+
                    '</div>'+
                '</div>'+
                '<div class="col-md-3">'+
                    '<input type="number" name="color_qty[]" class="input-field"'+
                        'placeholder="" value="1" min="1">'+
                '</div>'+
                '<div class="col-md-3">'+
                    '<input type="number" step="0.01" name="color_price[]" class="input-field"'+
                        'placeholder="" value="0" min="0">'+
                '</div>'+
                '<div class="col-md-3">'+
                    '<button type="button" class="btn btn-danger text-white color-remove"><i class="fa fa-times"></i></button>'+
                '</div>'+
            '</div>');
    $('.cp').colorpicker();
    }

        


});

// Color Section Ends

// Type Check

$('#type_check').on('change',function(){
    var val = $(this).val();
    if(val == 1) {
    $('.row.file').css('display','flex');
    $('.row.file').find('input[type=file]').prop('required',true);
    $('.row.link').find('textarea').val('').prop('required',false);
    $('.row.link').hide();
    }
    else {
    $('.row.file').hide();
    $('.row.link').css('display','flex');
    $('.row.file').find('input[type=file]').prop('required',false);
    $('.row.link').find('textarea').prop('required',true);
    }

});

// Type Check Ends



// License Section

$("#license-btn").on('click', function(){

    $("#license-section").append(''+
                            '<div class="license-area">'+
                                '<span class="remove license-remove"><i class="fas fa-times"></i></span>'+
                                    '<div  class="row">'+
                                        '<div class="col-lg-6">'+
                                            '<input type="text" name="license[]" class="input-field" placeholder="Chave de Licença" required="">'+
                                        '</div>'+
                                        '<div class="col-lg-6">'+
                                            '<input type="number" name="license_qty[]" min="1" class="input-field" placeholder="Quantidade de Licenças" value="1">'+
                                        '</div>'+
                                    '</div>'+
                            '</div>'
                            +'');
});

$(document).on('click','.license-remove', function(){

    $(this.parentNode).remove();
    if (isEmpty($('#license-section'))) {

    $("#license-section").append(''+
                            '<div class="license-area">'+
                                '<span class="remove license-remove"><i class="fas fa-times"></i></span>'+
                                    '<div  class="row">'+
                                        '<div class="col-lg-6">'+
                                            '<input type="text" name="license[]" class="input-field" placeholder="Chave de Licença" required="">'+
                                        '</div>'+
                                        '<div class="col-lg-6">'+
                                            '<input type="number" name="license_qty[]" min="1" class="input-field" placeholder="Quantidade de Licenças" value="1">'+
                                        '</div>'+
                                    '</div>'+
                            '</div>'
                            +'');
    }

});

// License Section Ends

$("#size-check").change(function() {
    if(this.checked) {
        $("#size-display").show();
        $("#stckprod").hide();
    }
    else
    {
        $("#size-display").hide();
        $("#stckprod").show();

    }
});

$("#whole_check").change(function() {
    if(this.checked) {
        $("#whole-section input").prop('required',true);
    }
    else {
        $("#whole-section input").prop('required',false);
    }
});


// Whole Sell Section

$("#whole-btn").on('click', function(){

    if(whole_sell > $("[name='whole_sell_qty[]']").length)
    {
    $("#whole-section").append(''+
                            '<div class="feature-area">'+
                                '<span class="remove whole-remove"><i class="fas fa-times"></i></span>'+
                                    '<div  class="row">'+
                                        '<div class="col-lg-6">'+
                                            '<input type="number" name="whole_sell_qty[]" class="input-field" placeholder="Digite a Quantidade" min="0" required>'+
                                        '</div>'+
                                        '<div class="col-lg-6">'+
                                            '<input type="number" name="whole_sell_discount[]" class="input-field" placeholder="Digite o Percentual de Desconto" min="0" required>'+
                                        '</div>'+
                                    '</div>'+
                            '</div>'
                            +'');        
    }

});

$(document).on('click','.whole-remove', function(){

    $(this.parentNode).remove();
    if (isEmpty($('#whole-section'))) {

    $("#whole-section").append(''+
                            '<div class="feature-area">'+
                                '<span class="remove whole-remove"><i class="fas fa-times"></i></span>'+
                                    '<div  class="row">'+
                                        '<div class="col-lg-6">'+
                                            '<input type="number" name="whole_sell_qty[]" class="input-field" placeholder="Digite a Quantidade" min="0">'+
                                        '</div>'+
                                        '<div class="col-lg-6">'+
                                            '<input type="number" name="whole_sell_discount[]" class="input-field" placeholder="Digite o Percentual de Desconto" min="0">'+
                                        '</div>'+
                                    '</div>'+
                            '</div>'
                            +'');
    }

});

// Whole Sell Section Ends

// Redplay
function verifyRedplayLicenseCheck()
{
    let checked = $('#hasLicense').prop('checked');
    if(!checked)
        return $('#divProductLicenses').hide();

    $('#divProductLicenses').show();
}

$('#hasLicense').change(function () {
    verifyRedplayLicenseCheck();
});

$(document).ready(function () {
    verifyRedplayLicenseCheck();
});

$(document).on('click', '.btnAddRedplay', function () {
    let redPlayRow =    '<div class="row mb-2">'+
                            '<div class="col-3">'+
                                '<input type="text" name="redplay_login[]" class="input-field m-0" placeholder="Login Redplay">'+
                            '</div>'+
                            '<div class="col-3">'+
                                '<input type="text" name="redplay_password[]" class="input-field m-0" placeholder="Senha Redplay">'+
                            '</div>'+
                            '<div class="col-3">'+
                                '<input type="text" name="redplay_code[]" class="input-field m-0" placeholder="Código Redplay">'+
                            '</div>'+
                            '<div class="col-1 d-flex align-items-center justify-content-center">'+
                                '<button type="button" class="btn btn-info bg-dark border border-dark btnAddRedplay">'+
                                    '<span aria-hidden="true"><i class="fa fa-plus"></i></span>'+
                                '</button>'+
                                '<button type="button" class="btn btn-danger border border-red btnRemoveRedplay ml-2">'+
                                    '<span aria-hidden="true"><i class="fa fa-trash"></i></span>'+
                                '</button>'+
                            '</div>'+
                            '<div class="col-1 d-flex align-items-center justify-content-center">'+
                                '<span class="badge badge-success ml-2">Disponível</span>'+
                            '</div>'+
                        '</div>';
    let redPlayInputArea = $('#redplayInputArea');

    redPlayInputArea.append(redPlayRow);
});

$(document).on('click', '.btnRemoveRedplay', function () {
    $(this).parent().parent().remove();
});


})(jQuery);


  

