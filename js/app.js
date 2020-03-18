$(document).ready(function() {

  $.validate({
    lang: 'es',
    errorMessagePosition: 'bottom',
    scrollToTopOnError: true,
    modules: 'file'
  });

  $('#inicio').timepicker({
    timeFormat: 'H:mm ',
    interval: 5,
    maxTime: '22:30',
    minTime: '08:00',
    startTime: '08:00',
    dynamic: true,
    dropdown: true,
    scrollbar: true
  });

  $('#fin').timepicker({
    timeFormat: 'H:mm ',
    interval: 5,
    maxTime: '23:30',
    minTime: '08:00',
    startTime: '08:00',
    dynamic: true,
    dropdown: true,
    scrollbar: true
  });

var today = new Date();

  $(function() {
    $("#fecha").datepicker({
      minDate: today,
      dateFormat: 'dd-mm-yy'
    }
    );
  });

  $(function() {
    $("#fechaDesde").datepicker({
      dateFormat: 'dd-mm-yy'
    });
  });

  $(function() {
    $("#fechaHasta").datepicker({
      dateFormat: 'dd-mm-yy'
    });
  });

  $('#password, #password2').keypress(function(tecla) {
    if (tecla.charCode == 32) {
      alert('no se permiten espacios en la contraseña ;)');
      return false;
    }
  });

  $("#formularioLogin").submit(function(e) {

    e.preventDefault();

    const idEmpleado = $('input[name="idEmpleado"]').val();
    const password = $('input[name="password"]').val();
    const accion = $('input[name="accion"]').val();

    var datosAcceso = 'accion=' + accion + '&idEmpleadoLogin=' + idEmpleado + '&passwordLogin=' + password;

    $.ajax({
      url: '../controller/empleadoControlador.php',
      type: 'POST',
      data: datosAcceso,
      processData: false,
      success: function(respuestaAjax) {
        console.log(respuestaAjax);
        if (respuestaAjax == 1) {
          Swal.fire({
            position: 'center',
            type: 'success',
            title: 'Login Correcto',
            showConfirmButton: false,
            timer: 1500
          })
          setTimeout(function() {
            window.location.replace("../index.php");
          }, 2000);
        } else {
          Swal.fire({
            type: 'error',
            title: 'Oops...',
            text: 'ID o password incorrecto',
            showConfirmButton: false,
            timer: 1500
          })
        }
      }
    });
  });

  $("#formularioAltaEmpleados").submit(function(e) {

    e.preventDefault();

    const password = $('input[name="password"]').val();
    const password2 = $('input[name="password2"]').val();

    if (password != password2) {
      Swal.fire({
        type: 'error',
        title: 'Las contraseñas no coinciden',
        showConfirmButton: false,
        timer: 1500
      })
      return false;
    }

    var datos = $("#formularioAltaEmpleados").serialize();
    var foto = $('input[name="foto"]')[0].files[0];
    const infoEmpleado = new FormData();
    infoEmpleado.append('datos', datos);
    infoEmpleado.append('foto', foto);

    $.ajax({
      url: '../controller/empleadoControlador.php',
      type: 'POST',
      data: infoEmpleado,
      contentType: false,
      processData: false,
      success: function(respuestaAjax) {
        console.log(respuestaAjax);
        if (respuestaAjax == 1) {
          Swal.fire({
            position: 'center',
            type: 'success',
            title: 'Realizado con Éxito',
            showConfirmButton: false,
            timer: 1500
          })
          setTimeout(function() {
          window.location.replace("../index.php");
          }, 2000);
        } else {
          Swal.fire({
            type: 'error',
            title: 'Oops...',
            text: 'Se Produjo un Error',
            showConfirmButton: false,
            timer: 1500
          })
        }
      }
    });

  });

  const id = $('input[name="id"]').val();
  const imgPreviewUpdating = $("#fotoLbl").attr("value");
  if (id && imgPreviewUpdating) {

    $('#formularioAltaEmpleados + img').remove();
    $('#fotoLbl').empty();
    $('#fotoLbl').append('<img src="' + imgPreviewUpdating + '" width="80" height="90" style="border-radius: 1rem; border: 1px solid #e1e1e1"/>');
  }

  const imgPreviewUpdatingEmp = $("#fotoLblEmp").attr("value");
  if (id && imgPreviewUpdatingEmp) {
    $('#formularioAltaEmpleados + img').remove();
    $('#fotoLblEmp').empty();
    $('#fotoLblEmp').append('<img src="' + imgPreviewUpdatingEmp + '" width="250" height="270" style="border-radius: 1rem; border: 1px solid #e1e1e1"/>');
  }

  function filePreview(input) {
    if (input.files && input.files[0]) {
      var reader = new FileReader();
      reader.onload = function(e) {
        $('#formularioAltaEmpleados + img').remove();
        $('#fotoLbl').empty();
        $('#fotoLblEmp').empty();
        $('#fotoLbl').append('<img src="' + e.target.result + '" width="80" height="90" style="border-radius: 1rem; border: 1px solid #e1e1e1"/>');
        $('#fotoLblEmp').append('<img src="' + e.target.result + '" width="250" height="270" style="border-radius: 1rem; border: 1px solid #e1e1e1;"/>');
      }
      reader.readAsDataURL(input.files[0]);
    }
  }

  $("#foto").change(function() {
    filePreview(this);
  });

  $(".planInvite").click(function() {
  const idInvitado = $(this).attr('data-id');
  const idReunion = QueryString.id;
  const email = $(this).attr('data-mail');
  const dataPosition = $(this).attr('data-position');

  //console.log(idInvitado);
  //console.log(idReunion);
  //console.log(email);
  //console.log(dataPosition);

  var url = `../controller/empleado_reunion_controlador.php?idEmpleado_fk=${idInvitado}&email=${email}&idReunion_fk=${idReunion}&accion=invitar`;

  $.ajax({
    url: url,
    type: 'GET',
    processData: false,
    beforeSend: function(){
      console.log('cargando');
      $(".planInvite").each(function(index){
        console.log(index);
        console.log(dataPosition);
        if (index == dataPosition) {
        Swal.fire({
          position: 'center',
          title: 'Espere...',
          imageUrl: '../img/utilImages/ajax-loader.gif',
          imageWidth: 100,
          imageHeight: 100,
          imageAlt: 'Loader',
          showConfirmButton: false,
          allowOutsideClick: false,
          allowEscapeKey: false,
          allowEnterKey: false,
        })
        $(".planInvite")[dataPosition].remove();
        }
      });
      },
    success: function(respuestaAjax) {
      //console.log(respuestaAjax);
      if (respuestaAjax == 1) {
        Swal.fire({
          position: 'center',
          type: 'success',
          title: 'Empleado Invitado',
          showConfirmButton: false,
          timer: 1500,
          allowOutsideClick: false,
          allowEscapeKey: false,
          allowEnterKey: false,
        })
      }
      else
      {
        Swal.fire({
          type: 'error',
          title: 'Oops...',
          text: 'Se Produjo un Error',
          showConfirmButton: false,
          timer: 1500
        })
      }
    }
  });

});

$("#findMeetingForm").submit(function(e) {

e.preventDefault();

const fechaDesde = $('#fechaDesde').val();
const fechaHasta = $('#fechaHasta').val();
const accion = $('input[name="accion"]').val();

var fechas = 'accion=' + accion + '&fechaDesde=' + fechaDesde + '&fechaHasta=' + fechaHasta;

var costeAcumulado = 0;
var iNum;

var request = $.ajax({
  url: '../controller/empleado_reunion_controlador.php',
  type: 'POST',
  data: fechas,
  dataType: 'json',
});

request.done(function(response) {
  var idAux = 0;
  //console.log(response)
  if (response.length > 0) {
    $(".tabla_reuniones tbody").html("");
    $.each(response, function (index,item) {
      //console.log(`${item.id} - ${item.asunto} - ${item.fecha} - ${item.inicio} - ${item.fin} - ${item.costeEstimado}\n`);
      if (`${item.id}` != idAux) {
        $(".tabla_reuniones tbody").append("<tr id='reportBydates' idReunionAttr="+`${item.id}`+"><td>"+`${item.asunto}`+"</td><td>"+`${item.fecha}`+"</td><td>"+`${item.inicio}`+"</td><td>"+`${item.fin}`+"</td><td>"+`${item.costeEstimado}`+"</td></tr>");
        idAux = `${item.id}`;
        iNum = parseInt(`${item.costeEstimado}`);
        costeAcumulado += iNum;
      }
      $(".tabla_reuniones tbody").append("<tr><td>"+`${item.nombre}`+" "+`${item.apellidos}`+"</td><td>"+`${item.departamento}`+"</td><td>"+`${item.costeHora}`+"</td></tr>");
      });
      $('#costeEstimado').html(costeAcumulado);
      $('#checks').parent().hide();
      $('#toHide').hide();
      $( ".reportLink").unbind( "click" );
      $('.reportLink').html("<div id='reportLinkButton'><i style='margin-right:1rem;' class='far fa-file-excel'></i>Exportar Excel</div>");
      $('.reportLink').click(function() {
        var arrayIds = [];
        $('tbody #reportBydates').each(function(){
          arrayIds.push($(this).attr('idReunionAttr'));
        });
          $.ajax({
            url: `../controller/meetingReport.php?arrayIds=${arrayIds}&tipo=global`,
            type: 'GET',
            processData: false,
            success: function(data) {
                var opResult = JSON.parse(data);
                var $a=$("<a>");
                $a.attr("href",opResult.data);
                //$a.html("LNK");
                $("body").append($a);
                $a.attr("download","meetingReport.xlsx");
                $a[0].click();
                $a.remove();
            },
          });
      });

  } else {
    Swal.fire({
      type: 'info',
      title: 'No encontrado',
      text: 'No existen reuniones en el periodo seleccionado',
      showConfirmButton: false,
      timer: 5000
    })
  }

});

request.fail(function(jqXHR, textStatus, response) {
    alert("Error en la petición: " + textStatus +response);
});

});

$("tbody #idDetail").each(function(){
      $(this).hide();
});

$(".details").each(function(){
  $(this).click(function(e) {
    var idReunion = e.target.getAttribute("idReunionAttr");
    $("tbody #idDetail").each(function(){
    if($(this).attr("idDetailAttr") == idReunion && !$(this).hasClass('visible')) {
      $(this).show();
      $(this).addClass('visible');
      $(e.target).removeClass("fa-search");
      $(e.target).addClass("fa-times-circle");
    }
    else if ($(this).attr("idDetailAttr") == idReunion && $(this).hasClass('visible'))
    {
      $(this).hide();
      $(this).removeClass('visible');
      $(e.target).removeClass("fa-times-circle");
      $(e.target).addClass("fa-search");
    }
    });
  });
});

$('#checks').click(function(e) {
  $('tbody #mycheckbox').each(function(){
    if ($('#checks').prop('checked')){
      $('tbody #mycheckbox').prop('checked',true)
    }
    else
    {
      $('tbody #mycheckbox').prop('checked',false)
    }
  });
});


$('.reportLink').click(function() {
  console.log('no has desactivado el click')
  var arrayIds = [];

  $('tbody #mycheckbox').each(function(){
    if ($(this).prop('checked')) {
      arrayIds.push($(this).val());
    }
  });

  if (arrayIds.length == 0) {
    Swal.fire({
      type: 'info',
      title: 'No hay reuniones seleccionadas',
      text: 'Debe seleccionar al menos un registro',
      showConfirmButton: false,
      timer: 4000
    })
  }
  else
  {
    $.ajax({
      url: `../controller/meetingReport.php?arrayIds=${arrayIds}&tipo=global`,
      type: 'GET',
      processData: false,
      success: function(data) {
          var opResult = JSON.parse(data);
          var $a=$("<a>");
          $a.attr("href",opResult.data);
          //$a.html("LNK");
          $("body").append($a);
          $a.attr("download","meetingReport.xlsx");
          $a[0].click();
          $a.remove();
      },

    });
  }

});


//--------------------------------------------------------------------------------------------FINAL DE JQUERY--------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------FINAL DE JQUERY--------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------FINAL DE JQUERY--------------------------------------------------------------------------------------------

});

const listaEmpleados = document.querySelector('#plans');

const listaEmpleadosEliminar = document.querySelector('#employer-plan');

const listaReuniones = document.querySelector('#meeting-plan');

const inputBuscador = document.querySelector('#buscar');

const anula_invitacion = document.querySelector('#plan-lista-asistentes');

const formularioEmpleados = document.querySelector('#altaEmpleados');

const formularioActualizarEmpleados = document.querySelector('#actualizarEmpleados');

const botonConfirmar = document.querySelector('#plan-lista-asistentes');

var QueryString = function() {

  var query_string = {};
  var query = window.location.search.substring(1);
  var vars = query.split("&");
  for (var i = 0; i < vars.length; i++) {
    var pair = vars[i].split("=");
    if (typeof query_string[pair[0]] === "undefined") {
      query_string[pair[0]] = decodeURIComponent(pair[1]);
    } else if (typeof query_string[pair[0]] === "string") {
      var arr = [query_string[pair[0]], decodeURIComponent(pair[1])];
      query_string[pair[0]] = arr;
    } else {
      query_string[pair[0]].push(decodeURIComponent(pair[1]));
    }
  }
  return query_string;
}();


function decimalAdjust(type, value, exp) {
  // Si el exp no está definido o es cero...
  if (typeof exp === 'undefined' || +exp === 0) {
    return Math[type](value);
  }
  value = +value;
  exp = +exp;
  // Si el valor no es un número o el exp no es un entero...
  if (isNaN(value) || !(typeof exp === 'number' && exp % 1 === 0)) {
    return NaN;
  }
  // Shift
  value = value.toString().split('e');
  value = Math[type](+(value[0] + 'e' + (value[1] ? (+value[1] - exp) : -exp)));
  // Shift back
  value = value.toString().split('e');
  return +(value[0] + 'e' + (value[1] ? (+value[1] + exp) : exp));
}

eventListeners();

function eventListeners() {

  if (listaEmpleadosEliminar) {
    listaEmpleadosEliminar.addEventListener('click', eliminarEmpleado);
    listaEmpleadosEliminar.addEventListener('click', editarEmpleado);
  }

  if (listaReuniones) {
    listaReuniones.addEventListener('click', eliminarReunion);
  }

  if (inputBuscador) {
    inputBuscador.addEventListener('input', buscarEmpleados);
  }
  if (anula_invitacion) {
    anula_invitacion.addEventListener('click', anulaInvitacion);
  }

  if (formularioActualizarEmpleados) {
    formularioActualizarEmpleados.addEventListener('submit', leerFormularioActualizar);
  }

  if (botonConfirmar) {
    botonConfirmar.addEventListener('click', confirmarInvitacion);
  }

}

function editarEmpleado(e) {

  e.preventDefault();

  if (e.target.parentElement.classList.contains('employer-plans-edit')) {

    const id = e.target.parentElement.getAttribute('data_id_employer');

    console.log('editar' + id);

    window.location.replace("../views/empleadoAlta.php?id=" + id);

  }

}

function eliminarEmpleado(e) {

  e.preventDefault();

  if (e.target.parentElement.classList.contains('employer-plans-delete')) {

    const id = e.target.parentElement.getAttribute('data_id_employer');

    console.log('eliminar' + id);

    console.log(id);

    Swal.fire({
      title: 'Está seguro/a?',
      text: "Esta opción no será reversible",
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Eliminar'
    }).then((result) => {
      if (result.value) {
        const xhr = new XMLHttpRequest();

        //Abrir la conexión
        xhr.open('GET', `../controller/empleadoControlador.php?id=${id}&accion=eliminar`, true);

        //Pasar los datos
        xhr.onload = function() {
          if (this.status === 200) {
            console.log(xhr.responseText);
            e.target.parentElement.parentElement.remove();
          }
        }
        xhr.send();
        Swal.fire(
          '¡ELIMINADO/A!',
          'EMPLEADO/A ELIMINADO/A CON ÉXITO',
          'success'
        )
      }
    })

  }
}

function confirmarInvitacion(e) {

  if (e.target.classList.contains('target')) {

    const idNexo = e.target.parentElement.getAttribute('data-id-nexo');

    console.log(idNexo);
    const xhr = new XMLHttpRequest();

    xhr.open('GET', `../controller/empleado_reunion_controlador.php?idNexo=${idNexo}&accion=confirmar`, true);

    xhr.onload = function() {
      if (this.status === 200) {
        console.log(xhr.responseText);
        Swal.fire({
          position: 'center',
          type: 'success',
          title: 'Asistencia confirmada',
          showConfirmButton: false,
          timer: 1500
        })
        setTimeout(() => {

          const divConfirmacion = document.createElement('div');
          divConfirmacion.classList.add('btn-confirma-invitacion');

          const iconoConfirmado = document.createElement('i');
          iconoConfirmado.classList.add('fas', 'fa-check-circle');

          const p = document.createElement('p');

          divConfirmacion.appendChild(iconoConfirmado);
          divConfirmacion.appendChild(p);

          p.innerHTML = 'Asistencia Confirmada';

          const divBoton = e.target.parentElement.parentElement;

          divBoton.appendChild(divConfirmacion);

          e.target.parentElement.remove();

          iconoConfirmado.removeEventListener("click", confirmarInvitacion);


        }, 2000);
      }

    }

    xhr.send();
  }
}

function eliminarReunion(e) {

  if (e.target.parentElement.classList.contains('meeting-plans-delete')) {

    const id = e.target.parentElement.getAttribute('data_id_meeting');

    console.log(id);

    Swal.fire({
      title: 'Está seguro/a?',
      text: "Esta opción no será reversible",
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Eliminar'
    }).then((result) => {
      if (result.value) {
        const xhr = new XMLHttpRequest();

        //Abrir la conexión
        xhr.open('GET', `../controller/reunionControlador.php?id=${id}&accion=eliminar`, true);

        //Pasar los datos
        xhr.onload = function() {
          if (this.status === 200) {
            console.log(xhr.responseText);
            e.target.parentElement.parentElement.remove();
          }
        }
        xhr.send();
        Swal.fire(
          '¡ELIMINADA!',
          'REUNÓN ELIMINADA CON ÉXITO',
          'success'
        )
      }
    })

  }
}

function anulaInvitacion(e) {

  e.preventDefault();

  if (e.target.parentElement.classList.contains('btn-anula-invitacion')) {

    const idNexo = e.target.parentElement.getAttribute('data-id-nexo');
    const idReunion = e.target.parentElement.getAttribute('data-id-reunion');
    const costeEmpleado = e.target.parentElement.getAttribute('data-id-costeEmpleado');
    let costeEstimadoObj = document.querySelector('#costeEstimado');
    const costeEstimado = costeEstimadoObj.innerHTML;
    var costeActualizado = costeEstimado - costeEmpleado;

    console.log(idNexo);
    console.log(costeEstimado - costeEmpleado);

    Swal.fire({
      title: 'Está seguro/a?',
      text: "Se enviará un aviso por mail al empleado",
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Eliminar'
    }).then((result) => {
      if (result.value) {
        const xhr = new XMLHttpRequest();

        //Abrir la conexión
        xhr.open('GET', `../controller/empleado_reunion_controlador.php?idNexo=${idNexo}&idReunion=${idReunion}&costeEmpleado=${costeEmpleado}&accion=anular_invitacion`, true);

        //Pasar los datos
        xhr.onload = function() {
          if (this.status === 200) {
            console.log(xhr.responseText);
            e.target.parentElement.parentElement.parentElement.remove();
            costeEstimadoObj.innerHTML = decimalAdjust('round', costeActualizado, -1);
          }
        }
        xhr.send();
        Swal.fire(
          '¡ANULADA!',
          'SI SE RETRACTA, REENVÍE LA INVITACIÓN',
          'success'
        )
      }
    })

  }
}

function buscarEmpleados(e) {
  //console.log(e.target.value);
  const expresion = new RegExp(e.target.value, "i");
  const registros = document.querySelectorAll('.plans .plan');

  registros.forEach(registro => {
    registro.style.display = 'none';

    //console.log(registro.childNodes[1].textContent.replace(/\s/g, " ").search(expresion) != -1);
    if (registro.childNodes[1].textContent.replace(/\s/g, " ").search(expresion) != -1) {
      registro.style.display = 'block';
    }

  })
}
