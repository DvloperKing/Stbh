
function login_formulario() {
    var formulario__login = document.querySelector(".formulario__login");
    var email     =   document.getElementById("email").value;
    var pass      =   document.getElementById("pass").value;

    console.log(email,pass);
    const formData = new FormData(formulario__login);
  
    upload(formData);        
 }

 async function upload(formData){
    try {
        const response = await fetch("../Core/API/login.php", {
            method: 'POST',
            body: formData
        });
        const result = await response.json();
        if (result.code==1){
            window.location.href="../pages/admin.php";
        }else if(result.code==2){
            window.location.href="../pages/alumno.php";
        }
        else {
            alert("Su correo institucional o contraseña son incorrectos");
        }
        console.log("Success:", result);
    }   catch (error) {
        console.error('Error:', error);
    }
}