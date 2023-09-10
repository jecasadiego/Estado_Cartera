$(document).ready(function () {
    $('#formbuscar').on('submit', function (e) {
        e.preventDefault()
        var nit = $('#nit').val()
        var buscarbtn = $('#buscarbtn')
        action = 'buscar'

        if (nit == '') {
            return alert('Porfavor digite un nit')
        }

        $.ajax({
            url: 'php/ajax.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                nit,
                action
            },
            beforeSend: function(){
                buscarbtn.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Cargando...')
            }
        })
        .done(function (res) {
            if (res.status === 200) {
                if(res.data['respuesta'] == 1){
                    setTimeout(retornarBoton,2000,buscarbtn,'success','¡Felicidades',res.data['texto'],res.data['pdf'],res.data['respuesta'])
                }else if(res.data['respuesta'] == 2){
                    setTimeout(retornarBoton,2000,buscarbtn,'info','¡Atención!',res.data['texto'],res.data['pdf'],res.data['respuesta'])
                }else if(res.data['respuesta'] == 3){
                    setTimeout(retornarBoton,2000,buscarbtn,'info','Referencia Comercial',res.data['texto'],res.data['pdf'],res.data['respuesta'])
                }
                
            }else if(res.status === 400) {
                setTimeout(retornarBoton,2000,buscarbtn,'error','¡Ups!', 'El cliente no existe dentro de nuestra base de datos.', null)
            }
        })
        .fail(function (err) {
            alert("Hubo un error con la petición, porfavor espere...");
        });
    })


    function retornarBoton(buscarbtn, icon, title, text, footer,respuesta){

        if(respuesta == 3){

            Swal.fire({
                icon: icon,
                title: title,
                html: text,
                footer: footer,
                width: '40rem'
            })
        }else{

            Swal.fire({
                icon: icon,
                title: title,
                html: text,
                footer: footer
            })
        }
        buscarbtn.html('Buscar')
    }
})

