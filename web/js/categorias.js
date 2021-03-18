$(document).ready(function(){
   $('#categoriaForm').on('change', function(){
       $('#mensajeVacio').html('');
       
       //modificamos la url el 5 elemento y el ultimo lo eliminamos
       var url = $(location).attr('href');
       var pathname = $(location).attr('pathname');
       var partesUrl = url.split('/'); 
       partesUrl[5] = 'categoria-ajax';
       partesUrl.pop(); 
       var urlCambiada = partesUrl.join('/');
       
       if($('#nombre').val()){
           $.ajax({
               type:'POST',
               url: urlCambiada,
               // url:'/trabaja21/categorias/categoria-ajax',
               data: $('#categoriaForm').serialize(),
               success:function(respuesta){
                  // console.log('Finalizamos bien '+respuesta.redirect);
                  // window.location = respuesta.redirect;
               }
           });
       }else{
           $('#cajaActivada').hide();
           $('#cajaDesactivada').hide();
           $('#mensajeVacio').html('<span class="badge badge-pill badge-danger">El valor Nombre no puede ser vacio</span>');
       }
   });

   $('#activo').on('click',function(){
       $('#cajaActivada').hide();
       $('#cajaDesactivada').hide();

       if($('#nombre').val()){
           if( $('#activo').is(':checked') ) {
               $('#etiquetaEstado').html('<span class="badge badge-pill badge-success">Activado</span>');
               $('#cajaActivada').show();
           }else{
               $('#etiquetaEstado').html('<span class="badge badge-pill badge-danger">Desactivado</span>');
               $('#cajaDesactivada').show();
           }
       }else{
           if( $('#activo').is(':checked') ) {
               $('#activo').prop('checked', false);
           }else{
               $('#activo').prop('checked', true);
           }
       }
   })

});