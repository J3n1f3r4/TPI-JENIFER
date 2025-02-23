const formRegister = document.querySelector(".form-register");
const inputUser = document.querySelector('.form-register input[type="text"]');
const inputPass = document.querySelector('.form-register input[type="password"]');
const inputEmail = document.querySelector('.form-register input[type="email"]');
const alertaError = document.querySelector(".form-register .alerta-error");
const alertaExito = document.querySelector(".form-register .alerta-exito");

/*se usa para decir se puede poner al momento de llenar el fomulario*/
const userNameRegex = /^[a-zA-Z0-9\_\-]{4,16}$/;
const emailRegex = /^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/;
const passwordRegex = /^.{4,12}$/;

const estadoValidacionCampos = {
  userName: false,
  userEmail: false,
  userPassword: false,
};

document.addEventListener("DOMContentLoaded", () => {
  formRegister.addEventListener("submit", (e) => {
    /*e.preventDefault();/*para q no se envie los datos del usuario por la url y sea mas seguro el envio de la informacion*/
    enviarFormulario(formRegister, alertaError,alertaExito,e);
  });

  inputUser.addEventListener("input", () => {
    validarCampo(userNameRegex,inputUser,"El usuario tiene que ser de 4 a 16 dígitos y solo puede contener, letras y guión bajo.");
  });

  inputEmail.addEventListener("input", () => {
    validarCampo(emailRegex,inputEmail,"El correo solo puede contener letras, números, puntos, guiones y guíon bajo.");
  });

  inputPass.addEventListener("input", () => {
    validarCampo(passwordRegex,inputPass,"La contraseña tiene que ser de 4 a 12 dígitos");
  });
});

function validarCampo(regularExpresion, campo, mensaje) {
  const validarCampo= regularExpresion.test(campo.value);
  if (validarCampo) {
    eliminarAlerta(campo.parentElement.parentElement);/* en caso de que este bien el campo de formulario se borra la alerta*/
    estadoValidacionCampos[campo.name] = true;
    campo.parentElement.classList.remove("error");/*para que no se repita el mensaje de alerta*/
    return;
  }
  estadoValidacionCampos[campo.name] = false;
  campo.parentElement.classList.add("error");
  mostrarAlerta(campo.parentElement.parentElement,mensaje);
}

function mostrarAlerta(referencia,mensaje) {
  eliminarAlerta(referencia);
  const alertaDiv = document.createElement("div");
  alertaDiv.classList.add("alerta");
  alertaDiv.textContent = mensaje;
  referencia.appendChild(alertaDiv);
}

function eliminarAlerta(referencia) {
  const alerta = referencia.querySelector(".alerta");

  if (alerta) alerta.remove();
}

function enviarFormulario(form, alertaError, alertaExito, e) {
  //VALIDAMOS EL ENVIO DE NUESTRO FORMULARIO

  if (estadoValidacionCampos.userName && estadoValidacionCampos.userEmail && estadoValidacionCampos.userPassword) {
    /*ACTUALIZACION DEL CODIGO - RESETEAMOS A FALSE LAS PROPIEDADES DEL OBJETO*/
    estadoValidacionCampos.userName = false;
    estadoValidacionCampos.userEmail = false;
    estadoValidacionCampos.userPassword = false;

    //formRegister.reset();/*para que se limpie los campos cuando el usuario lo llene correctamente */
    alertaExito.classList.add("alertaExito");
    alertaError.classList.remove("alertaError");
    setTimeout(() => {
      alertaExito.classList.remove("alertaExito");
    }, 3000); 
    return;
  }
  e.preventDefault();
  alertaExito.classList.remove("alertaExito");
  alertaError.classList.add("alertaError");
  setTimeout(() => {
    alertaError.classList.remove("alertaError");
  }, 3000);
}