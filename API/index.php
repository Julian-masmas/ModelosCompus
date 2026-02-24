<?php require_once "connectDB.php"; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="styles.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultar Usuarios</title>
</head>
<body>

    <h1>Consultar Usuarios</h1>

    <label>Consultar:</label>
    <input type="text">
    <button>Agregar Cliente</button>

    <table>
        <caption>Clientes</caption>
        <thead>
            <tr>
                <th>ID Cliente</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Celular</th>
                <th>Opciones</th>
            </tr>
        </thead>
        <tbody id="tablaClientes">
            <!-- Aquí se insertarán los datos dinámicamente -->
        </tbody>
    </table>

<script>
document.addEventListener("DOMContentLoaded", () => {

    fetch("getClients.php")
        .then(response => response.json())
        .then(data => {

            const tabla = document.getElementById("tablaClientes");
            tabla.innerHTML = "";

            data.forEach(client => {

                const fila = document.createElement("tr");

                fila.innerHTML = `
                    <td>${client.id_client}</td>
                    <td>${client.name}</td>
                    <td>${client.correo}</td>
                    <td>${client.celular}</td>
                    <td>
                        <button title="Editar">✏</button>
                        <button title="Ver">👁</button>
                        <button title="Eliminar">🗑</button>
                    </td>
                `;

                tabla.appendChild(fila);
            });
        })
        .catch(error => {
            console.error("Error:", error);
        });

});
</script>

</body>
</html>