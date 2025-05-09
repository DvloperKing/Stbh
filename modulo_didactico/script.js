$(document).ready(function () {
    $("#uploadForm").on("submit", function (e) {
        e.preventDefault();
        let formData = new FormData(this);

        $.ajax({
            url: "procesar_archivos.php",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function (response) {
                try {
                    let res = JSON.parse(response);
                    alert(res.mensaje);
                    location.reload();
                } catch {
                    alert("Error inesperado en la subida.");
                }
            },
            error: function () {
                alert("Error al enviar los datos.");
            }
        });
    });
});

function eliminarArchivo(materia, archivo) {
    if (confirm("¿Seguro que quieres eliminar este archivo?")) {
        $.post("procesar_archivos.php", { materia: materia, eliminar: archivo }, function (response) {
            try {
                let res = JSON.parse(response);
                alert(res.mensaje);
                location.reload();
            } catch {
                alert("Error inesperado al eliminar.");
            }
        });
    }
}
