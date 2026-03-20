// #alerta en pantalla
function MyAlert(texto, tipo) {     
    if (tipo === "exito") {         
        $('#text-exito').text(texto);
        $('#alert-exito').removeClass('d-none');
        
        // Ocultar automáticamente después de 3 segundos (3000 ms)
        setTimeout(function() {
            $('#alert-exito').fadeOut(500, function() {
                $(this).addClass('d-none').show(); // Agrega la clase y asegura que esté visible la próxima vez
            });
        }, 3000);
        
    } else if (tipo === "error") {                  
        $('#text-error').text(texto);           
        $('#alert-error').removeClass('d-none');
        
        // Ocultar automáticamente después de 3 segundos (3000 ms)
         setTimeout(function() {
            $('#alert-error').fadeOut(500, function() {
                $(this).addClass('d-none').show(); // Agrega la clase y asegura que esté visible la próxima vez
            });
        }, 3000);
    }
}


//Validador de rut
function validaRut(input) {
    $('#error-rut').addClass('d-none'); 	
   
    // Obtener el valor del input
    let rut = input.value.trim();

    // Eliminar puntos, guiones y espacios
    rut = rut.replace(/[.\-\s]/g, '');

    // Dividir el RUT en número base y dígito verificador
    const numero = rut.slice(0, -1);
    const digitoVerificador = rut.slice(-1).toUpperCase();

    // Validar que el número base sea un número válido
    if (!/^\d+$/.test(numero) || !/^[0-9K]$/.test(digitoVerificador)) {
        input.value = ""; // Limpiar el campo
        $('#error-rut').removeClass('d-none'); 
        input.focus();
        return false;
    }

    // Calcular el dígito verificador esperado
    let suma = 0;
    let factor = 2;

    for (let i = numero.length - 1; i >= 0; i--) {
        suma += parseInt(numero[i]) * factor;
        factor = factor === 7 ? 2 : factor + 1;
    }

    const modulo = suma % 11;
    const digitoEsperado = modulo === 0 ? '0' : modulo === 1 ? 'K' : (11 - modulo).toString();

    // Comparar el dígito ingresado con el esperado
    if (digitoVerificador === digitoEsperado) {
        // Formatear el RUT y asignarlo al campo
        input.value = formatearRut(numero, digitoVerificador);
        return true;
    } else {
        input.value = ""; // Limpiar el campo
        $('#error-rut').removeClass('d-none'); 	
        input.focus();
        return false;
    }
}

// Función para formatear el RUT con puntos y guion
function formatearRut(numero, dv) {	
    return numero
        .replace(/\B(?=(\d{3})+(?!\d))/g, '.') // Agregar puntos cada 3 dígitos
        + '-' + dv; // Añadir el dígito verificador con guion
}


function formatCantidad(cantidad) {
    // Convertir a número
    cantidad = parseFloat(cantidad);

    // Si es un número entero, devolver sin decimales
    if (cantidad % 1 === 0) {
        return parseInt(cantidad, 10);
    }

    // Si tiene decimales, devolver tal cual
    return cantidad;
}

// Valida Campos Obligatorios
// Valida Campos Obligatorios
function ValidaCamposObligatorios(campos) {
    for (var i = 0; i < campos.length; i++) {
        const campoID = campos[i][0];
        const tipo = campos[i][1];
        const $campo = $('#' + campoID);

        // Validación para campos de texto y textarea
        if (tipo == 'text' || tipo == 'textarea') {
            if ($campo.val().trim() == '') {
                // Abrir acordeón padre si está colapsado
                const $accordion = $campo.closest('.accordion-collapse');
                if ($accordion.length && !$accordion.hasClass('show')) {
                    $accordion.addClass('show');
                }

                // Enfocar y resaltar
                $campo.focus().addClass('border border-danger')
                      .attr("placeholder", "Este campo es obligatorio");

                // Scroll al campo
                $('html, body').animate({
                    scrollTop: $campo.offset().top - 100
                }, 500);

                return false;
            }

        // Validación para campos de radio
        } else if (tipo == 'radio') {
            if ($('input[name="' + campoID + '"]:checked').length == 0) {
                $('#for_' + campoID).text(' Favor complete el campo*');

                // Scroll al contenedor de radio
                const $container = $('#for_' + campoID).closest('.accordion-collapse');
                if ($container.length && !$container.hasClass('show')) {
                    $container.addClass('show');
                }
                $('html, body').animate({
                    scrollTop: $('#for_' + campoID).offset().top - 100
                }, 500);

                return false;
            }

        // Validación para select
        } else if (tipo == 'select') {
            const selectVal = $campo.val();
            const largo = selectVal ? selectVal.length : 0;
            if (selectVal === '' || selectVal === null || largo === 0) {
                const $accordion = $campo.closest('.accordion-collapse');
                if ($accordion.length && !$accordion.hasClass('show')) {
                    $accordion.addClass('show');
                }

                $campo.focus().addClass('border border-danger');
                $('html, body').animate({
                    scrollTop: $campo.offset().top - 100
                }, 500);

                alert('Por favor, seleccione una opción');
                return false;
            }

        // Validación para number
        } else if (tipo == 'number') {
            const valor = $campo.val();
            if (valor === '' || isNaN(valor) || parseFloat(valor) <= 0) {
                const $accordion = $campo.closest('.accordion-collapse');
                if ($accordion.length && !$accordion.hasClass('show')) {
                    $accordion.addClass('show');
                }

                $campo.focus().addClass('border border-danger')
                      .attr("placeholder", "Ingrese un número válido");

                $('html, body').animate({
                    scrollTop: $campo.offset().top - 100
                }, 500);

                return false;
            }
        }
    }

    return true; // Todos los campos validados correctamente
}

function revertirFormatoMiles(numeroFormateado) {
    if (!numeroFormateado || numeroFormateado.trim() === "") {
        return 0;
    }

    const limpio = numeroFormateado
        .replace(/\./g, "")   // quita puntos de miles
        .replace(/\$/g, "")   // quita signo $
        .replace(/\s/g, "");  // quita espacios

    return limpio === "" ? 0 : parseFloat(limpio);
}




function formatearPrecio(precio) {
    return new Intl.NumberFormat('es-CL', {
        style: 'currency',
        currency: 'CLP'
    }).format(precio);
}

function TraduceEstados(estadoNum){
    const estados = {
        0: { texto: 'Diagnóstico', color: 'secondary' },
        1: { texto: 'Presupuesto', color: 'info' },
        2: { texto: 'Aprobado', color: 'primary' },
        3: { texto: 'Trabajando', color: 'warning' },
        4: { texto: 'Terminada', color: 'success' },
        5: { texto: 'Anulada', color: 'secondary' },
        6: { texto: 'Pagada', color: 'dark' }
    };

    // Retorna el estado correspondiente o un valor por defecto
    return estados[estadoNum] || { texto: 'Desconocido', color: 'dark' };
}