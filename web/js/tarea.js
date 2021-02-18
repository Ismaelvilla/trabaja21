$(document).ready(function(){
   //boton nuevaTarea
    $('#nuevaTarea').click(function(){
        //se activan los botones de Guardar y Cancelar
        $('#Guardar').attr("disabled", false);
        $('#Cancelar').attr("disabled", false);
        //se activa el campo de texto textNuevaTarea
        $('#textNuevaTarea').attr("disabled", false);
        //se desactiva el boton de NuevaTarea
        $('#nuevaTarea').attr("disabled", true);
    });

    //*********************** INICIO ACCIONES CANCELAR*************** //
    $('#Cancelar').click(function(){
        //tenemos que recorrer los checkbox y ver si hay alguno seleccionados
        var activaBotones = false;
        var id;
        var idSeleccionado;
        $('.check').each(function(){
            id = this.id;
            if( $('#'+id).prop('checked') ){
              activaBotones = true;
              idSeleccionado = id;
            }
        });

        if(activaBotones){
            CancelarModificar();
        }else{
            CancelarAnadir();
        }

    });

    function CancelarModificar(){
        $('#modalCancelar').modal('show');
    }

    function CancelarAnadir(){
        if($('#textNuevaTarea').val()){
            $('#modalCancelar').modal('show');
        }else{
            AccionesCancelar();
        }
    }

    $('#btnCancelarModalNo').click(function(){
        AccionesCancelar();
    });

    $('#btnCancelarModalSi').click(function(){
        $('#Guardar').click();
    });

    //Acciones llevadas a cabo cuando se cancela y no se guardan los cambios
    function AccionesCancelar(){
        //se Desactivan los 3 botones de cancelar, guardar, Eliminar
        estadoBotones(true);
        //desactivamos el texto textNuevaTarea
        $('#textNuevaTarea').attr("disabled", true);
        //ponemos vacio el textNueva Tareas
        $('#textNuevaTarea').val("");
        //activamos el boton NuevaTarea
        $('#nuevaTarea').attr("disabled", false);
        //Deseleccionamos todos los checkbox en el caso que estuvieran $seleccionados
        $('#gridTareas .check').prop('checked',false);
    }
    //************** FIN ACCIONES CANCELAR ******************//

    //************** INICIO ACCIONES GUARDAR ***************//
    $('#Guardar').click(function(){
        //tenemos que recorrer los checkbox y ver si hay alguno seleccionados
        var activaBotones = false;
        var id;
        var idSeleccionado;
        $('.check').each(function(){
            id = this.id;
            if( $('#'+id).prop('checked') ){
              activaBotones = true;
              idSeleccionado = id;
            }
        })
        //si es true, es modificar una tarea, si es false es añadir tarea nueva
        if(activaBotones){
            ModificarElemento(idSeleccionado);
        }else{
            AnadirElemento();
        }
    });

    function ModificarElemento(idSeleccionado){
        if(document.getElementById('textNuevaTarea').value){
            //ahora tenemos que modificar la tarea pasandole el id y el texto
            var json = {
              'id': idSeleccionado,
              'nombreTarea':$('#textNuevaTarea').val()
            }
            $.ajax({
              type: 'GET',
              data: json,
              url: 'modificar-ajax',
              success:function(respuesta){
                  $('#gridTareas').html('');
                  $('#gridTareas').html(respuesta);
                  $('#tareaVacia').html('<p id="alertSuccess" class="alert alert-success" role="alert">La tarea se ha modificado correctamente</p>')
                  $("#alertSuccess").delay(2000).slideUp(200, function() {
                      $(this).alert('close');
                  });
                  //se desactiva el texto y lo ponemos vacio
                  $('#textNuevaTarea').val("");
                  $('#textNuevaTarea').attr('disabled', true);
                  //desactivamos los activaBotones
                  estadoBotones(true);
              }
            });
        }else{
            $('#tareaVacia').html('<p id="alertDanger" class="alert alert-danger" role="alert">Debe escribir un nombre a la tarea</p>');
            $("#alertDanger").delay(2000).slideUp(200, function() {
                $(this).alert('close');
            });
        }
    }

    function AnadirElemento(){
        if(document.getElementById('textNuevaTarea').value){
            //se Desactivan los 3 botones de cancelar, guardar, Eliminar
            estadoBotones(true);
            //se activa el boton de NuevaTarea
            $('#nuevaTarea').attr('disabled', false);
            //hacemos el insert en la tabla tareas, mediante ajax
            var json = {
              'nombreTarea':$('#textNuevaTarea').val()
            }
            $.ajax({
              type: 'GET',
              data: json,
              url: 'nueva-ajax',
              success:function(respuesta){
                $('#gridTareas').html('');
                $('#gridTareas').html(respuesta);
                //se desactiva el texto y lo ponemos vacio
                $('#textNuevaTarea').val("");
                $('#textNuevaTarea').attr('disabled', true);
                //Ponermos un alert diciendo que se ha guardado y desaparece
                $('#tareaVacia').html('<p id="alertSuccess" class="alert alert-success" role="alert">La tarea se ha guardado correctamente</p>')
                $("#alertSuccess").delay(2000).slideUp(200, function() {
                    $(this).alert('close');
                });
              }
            });
        }else{
            $('#tareaVacia').html('<p id="alertDanger" class="alert alert-danger" role="alert">Debe escribir un nombre a la tarea</p>');
            $("#alertDanger").delay(2000).slideUp(200, function() {
                $(this).alert('close');
            });
        }
    }
    //************** FIN ACCIONES GUARDAR ******************//

    //************** INICIO ACCIONES ELIMINAR ******************//
    //boton de Eliminar
    $('#eliminarModal').click(function(){
        var seleccionados = '';
        //recorremos tods los checkbox para ver los que estan $seleccionados
        //los metemos en un string separados por el simbolo |
        $('.check').each(function(){
            var id = this.id;
            if( $('#'+id).prop('checked') ){
                seleccionados += id +'|';
            }
        });
        //creamos la variable json que es la que vamos a pasar por ajax
        var json={
            'seleccionados': seleccionados
        }
        $.ajax({
            method : 'GET',
            url: 'eliminar-ajax',
            data: json,
            success: function(respuesta){
                $('#gridTareas').html('');
                $('#gridTareas').html(respuesta);
                //desactivamos los 3 botones
                estadoBotones(true);
                //desactivamos y ponemos a vacio el textNuevaTarea
                $('#textNuevaTarea').val("");
                $('#textNuevaTarea').attr('disabled',true);
                //Ponermos un alert diciendo que se ha eliminado y desaparece
                $('#tareaVacia').html('<p id="alertDanger" class="alert alert-danger" role="alert">La tarea se ha eliminado correctamente</p>')
                $("#alertDanger").delay(2000).slideUp(200, function() {
                    $(this).alert('close');
                });

            }
        })
    });
    //************** FIN ACCIONES ELIMINAR ******************//

    function estadoBotones(estado){
        //si es true se desactivan los botones, si es false se activan
        $('#Guardar').attr("disabled", estado);
        $('#Cancelar').attr("disabled", estado);
        $('#Eliminar').attr("disabled", estado);
    }

    //cada vez que se haga click en un checkbox comprobamos que
    // si hay alguno seleccionado los botones estaran activados y
    //si no hay ninguno seleccionado los botones estaran desactivados
    $('#gridTareas').on('click', '.check', function(e){
        var activaBotones = false;
        var contadorSeleccionados = 0;
        var id = 0;
        var idSeleccionado=0;
        //recorremos todos los checkbox
        $(".check").each(function () {
            //sacamos su atributo id
            id = this.id;
            if( $('#'+id).prop('checked') ){
                activaBotones = true;
                contadorSeleccionados++;
                idSeleccionado = id;
            }
        });
        // si activaBotones es false se desactivan los botones
        if( activaBotones == false ){
          estadoBotones(true);
        }else{
          //activamos los activaBotones
          estadoBotones(false);
        }
        //vamos a comprobar para el boton guardar y cancelar si hay uno seleccionado, estara activado
        //si hay 0 o mas de 1 seleccionado el boton guardar y cancelar estara desactivado
        if(contadorSeleccionados == 1){
            $('#Guardar').attr("disabled", false);
            $('#Cancelar').attr("disabled", false);
            $('#textNuevaTarea').attr("disabled", false);
            $('#tarea'+idSeleccionado).val();
            $('#textNuevaTarea').val( $('#tarea'+idSeleccionado).val() );
        }else{
            $('#Guardar').attr("disabled", true);
            $('#Cancelar').attr("disabled", true);
            $('#textNuevaTarea').attr("disabled", true);
            $('#textNuevaTarea').val("");
        }
    });
})
