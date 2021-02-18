$(document).ready(function(){
    $('#deleteButton').click(function(){
        $.ajax({
            type: 'DELETE',
            dataType: 'json',
            success: function(respuesta){
                window.location = respuesta.redirect;
            }
        });
    });

    $('#aceptar').click(function(e){
       $('#seleccionarCategoria').html('');

       //el seleccione categoria vale 8
       if($('#categoria').val() == 8 ){
           e.preventDefault();
           $('#seleccionarCategoria').append('<p class="text-danger">Debes seleccionar una categoria</p>');
       }

       //el seleccione provincia vale 53
       if($('#provincia').val() == 53 ){
           e.preventDefault();
           $('#seleccionarCategoria').append('<p class="text-danger">Debes seleccionar una provincia</p>');
       }

    });

    $('#provincia').on('change',function(){
        var json = {
            'idEmpresa':$('#idEmpresa').val(),
            'idProvincia':this.value
        }
        $.ajax({
            url: '/empresas/provincia-ajax',
            type: 'GET',
            data: json,
            success: function(respuesta){
                console.log('terminamos con provincia: '+respuesta);
                $("#cajaMunicipio").html('');
                $("#cajaMunicipio").html(respuesta);
            },
            error : function(xhr, status) {
                alert('Disculpe, existió un problema: '+xhr);
            }
        });
    });

});