$(document).ready(function() {
    dt_teacher();

    $(document).on("click","#btn_show_user",function(){  
        let id=$(this).attr('data-id');
        $.ajax({
            url:"/usuarios/show/"+id,
            type:"GET",
            dataType:"JSON",
            success:function(data){
                $("#id_user").val(data.id);
                $("#identificacion").val(data.identificacion);
                $("#nombre").val(data.name);
                $("#email").val(data.email);
                $("#telefono").val(data.telefono);
                $("#celular").val(data.celular);
                $("#direccion").val(data.direccion);
                $("#nacimiento").val(data.fecha_nacimiento);
                $("#expedicion").val(data.lugar_expedicion);
                $('#genero >option[value='+data.genero+']').attr('selected',true).trigger('change');
                $('#estado >option[value=' + data.estado + ']').attr('selected',true).trigger('change');
            }
        });
    });     
    $(document).on("click","#btn_update_users",function(){ 
        $('#form_updateUser').validate({            
            rules:{
                nombre:{ required:true },                    
            },          
            submitHandler: function(form){
                
                var id=$('#id_user').val();
                let formData = new FormData($('#form_updateUser')[0]);
                formData.append('id_user',id);
                $.ajax({
                    url:"/usuarios/update",
                    type:"POST",
                    data:formData,
                    processData: false,
                    contentType: false,
                    cache: false,
                    timeout: 600000,
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},                    
                   success:function(data){
                       if(data==1){
                        sweetMessage('\u00A1Registro exitoso!', '\u00A1 Se ha realizado con \u00E9xito su solicitud!');
                        setTimeout(function () { location.reload() }, 2000)
                       }
        
                   }
                });
         }

        });

    });


    $(document).on("click","#submit_users",function(e){                                
         $('#form_createUser').validate({            
            rules:{
                    identificacion:{ required:true },                    
                }, 
                  messages : {
                    identificacion: {
                        required: "Hey vamos, por favor, dános la identificacion"
                    }
            },            
            showErrors: function (errorMap, errorList) {
                var errors = this.numberOfInvalids();
                if (errorList.length>0) {
                var message = errors == 1
                ? 'La creacion de usuarios  tiene 1 error'
                : 'La creacion de usuarios tiene ' + errors + ' errores.';
                message = message + ' Por favor completa los campos requeridos .'
                sweetMessage('\u00A1Atenci\u00f3n!',message,'warning');
            } 
            this.defaultShowErrors();
            },submitHandler: function(form1){

                let dni=$('input[name="identificacion"]').val();
                let formData = new FormData($('#form_createUser')[0]);
                $.ajax({
                    url:"/usuarios/create",
                    type:"POST",
                    data:formData,
                    processData: false,
                    contentType: false,
                    cache: false,
                    timeout: 600000,
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},                    
                   success:function(data){
                       if(data==1){
                        sweetMessage('\u00A1Registro exitoso!', '\u00A1 Se ha realizado con \u00E9xito su solicitud!');
                        setTimeout(function(){ window.location.replace('/usuarios/inicio') },1000) ;   
                       }else{
                        sweetMessage('\u00A1Atenci\u00f3n!', 'El usuario con identificacion '+ dni +'  ya se encuentra registrado en la Base de datos. ', 'warning');
                       }
        
                   }
                });
         }
        });

    });
});


var dt_teacher=function(){
    $('#dt_teacher').DataTable({        
          dom: 'Bfrtip',
          buttons: [
              'copy', 'csv', 'excel', 'pdf', 'print'
          ],      
        ajax: {
            url: "/dt_user",
            method: "GET", 
            dataSrc: function (json) {
                if (!json.data) {
                    return [];
                } else {
                    return json.data;
                }
              }               
            },
        columnDefs: [
            { width: '5%', targets: 0 },
            {"className": "text-center", "targets": "_all"},
            { orderable: true, className: 'reorder', targets: 0 },
            { orderable: true, className: 'reorder', targets: 6 },            
            { orderable: false, targets: '_all' }
        ],        
        columns: 
        [       
                { "data": "con" , render(data){return '<b>'+data+'</b>';}},         
                { "data": "dni" , render(data){return '<a class="text-primary">'+data+'</a>';}},
                { "data": "name",render(data,type,row){ return '<div class="text-info">'+  data +'</div>'; }},
                { "data": "celular"},
                { "data": "genero"},
                {"data": "cargo"},                 
                {"data": "estado", render(data){ let st=(data)? 'Activo':'Inactivo'; let color=(data)? 'success':'danger'; return  '<label class="badge text-white badge-'+color+' ">'+ st  +'</label>'; }},
                {"data": "", 
                    render(data,ps,d){ 
                        let button;
                        button='<div id="btn_show_user"  data-id='+d.id+'><i data-toggle="modal" data-target="#mdl_editUser" class="mdi mdi-pencil-box-outline text-primary" style="font-size:30px;"></i></div>';                        
                        return button; 
                    }
                },
        ]
    });
}