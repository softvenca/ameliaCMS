aajs_language={required_message_alert:" obligatorio ",url_message_alert:" url ",number_message_alert:" numerico ",letter_message_alert:" letras ",email_message_alert:" email ",min_message_alert:function(r){return" longitud de caracteres minima: "+r+" "},empty_alert:function(){toastr.error("Error: No debe dejar campos obligatorios vacios","",{progressBar:!0})},email_alert:function(){toastr.error("Error: La dirección de correo es incorrecta.","",{progressBar:!0})},min_alert:function(){toastr.error("Error: Existen campos que no cumplen el minimo de longitud requerido.","",{progressBar:!0})}};