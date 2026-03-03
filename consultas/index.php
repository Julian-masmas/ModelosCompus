<?php require_once "connectDB.php"; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="styles.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultar Usuarios</title>
    <style>
        /* Estilos para el modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.4);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 40%;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover {
            color: black;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-group input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .btn-submit {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-right: 10px;
        }

        .btn-cancel {
            background-color: #f44336;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .btn-submit:hover {
            background-color: #45a049;
        }

        .btn-cancel:hover {
            background-color: #da190b;
        }

        button {
            margin: 0 5px;
            cursor: pointer;
        }

        button[title="Editar"] {
            background-color: #ffc107;
            border: none;
            border-radius: 3px;
            padding: 5px 10px;
        }

        button[title="Ver"] {
            background-color: #17a2b8;
            border: none;
            border-radius: 3px;
            padding: 5px 10px;
        }

        button[title="Eliminar"] {
            background-color: #dc3545;
            border: none;
            border-radius: 3px;
            padding: 5px 10px;
            color: white;
        }

        .search-container {
            margin-bottom: 20px;
        }

        #searchInput {
            padding: 8px;
            width: 300px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .btn-agregar {
            background-color: #28a745;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-left: 10px;
        }
    </style>
</head>

<body>

    <div class="search-container">
        <input type="text" id="searchInput" placeholder="Buscar clientes...">*/
    </div>
    <button class="btn-agregar" onclick="abrirModalAgregar()">Agregar Cliente</button>

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

    <!-- Modal para Visualizar Cliente -->
    <div id="modalVer" class="modal">
        <div class="modal-content">
            <span class="close" onclick="cerrarModal('modalVer')">&times;</span>
            <h2>Detalles del Cliente</h2>
            <div id="detallesCliente"></div>
        </div>
    </div>

    <!-- Modal para Editar/Agregar Cliente -->
    <div id="modalEditar" class="modal">
        <div class="modal-content">
            <span class="close" onclick="cerrarModal('modalEditar')">&times;</span>
            <h2 id="modalTitulo">Editar Cliente</h2>
            <form id="formCliente">
                <input type="hidden" id="clienteId" name="id_cliente">
                <div class="form-group">
                    <label for="nombre">Nombre:</label>
                    <input type="text" id="nombre" name="nombre" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="celular">Celular:</label>
                    <input type="tel" id="celular" name="celular" required>
                </div>
                <div>
                    <button type="submit" class="btn-submit">Guardar</button>
                    <button type="button" class="btn-cancel" onclick="cerrarModal('modalEditar')">Cancelar</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Función para cargar los clientes
        function cargarClientes() {
            fetch("getClients.php")
                .then(response => response.json())
                .then(data => {
                    const tabla = document.getElementById("tablaClientes");
                    tabla.innerHTML = "";

                    data.forEach(client => {
                        const fila = document.createElement("tr");
                        fila.innerHTML = `
                    <td>${client.id_cliente}</td>
                    <td>${client.name}</td>
                    <td>${client.correo}</td>
                    <td>${client.celular}</td>
                    <td>
                        <button title="Editar" onclick="abrirModalEditar(${client.id_cliente}, '${client.name}', '${client.correo}', '${client.celular}')">✏</button>
                        <button title="Ver" onclick="verCliente(${client.id_cliente})">👁</button>
                        <button title="Eliminar" onclick="eliminarCliente(${client.id_cliente})">🗑</button>
                    </td>
                `;
                        tabla.appendChild(fila);
                    });
                })
                .catch(error => {
                    console.error("Error:", error);
                });
        }

        // Función para buscar clientes
        document.getElementById("searchInput").addEventListener("keyup", function() {
            let searchText = this.value.toLowerCase();
            let rows = document.querySelectorAll("#tablaClientes tr");

            rows.forEach(row => {
                let text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchText) ? "" : "none";
            });
        });

        // Función para abrir modal de ver cliente
        function verCliente(id) {
            fetch(`getClientById.php?id=${id}`)
                .then(response => response.json())
                .then(client => {
                    const detalles = document.getElementById("detallesCliente");
                    detalles.innerHTML = `
                <p><strong>ID:</strong> ${client.id_cliente}</p>
                <p><strong>Nombre:</strong> ${client.name}</p>
                <p><strong>Email:</strong> ${client.correo}</p>
                <p><strong>Celular:</strong> ${client.celular}</p>
            `;
                    document.getElementById("modalVer").style.display = "block";
                });
        }

        // Función para abrir modal de agregar cliente
        function abrirModalAgregar() {
            document.getElementById("modalTitulo").textContent = "Agregar Cliente";
            document.getElementById("clienteId").value = "";
            document.getElementById("nombre").value = "";
            document.getElementById("email").value = "";
            document.getElementById("celular").value = "";
            document.getElementById("modalEditar").style.display = "block";
        }

        // Función para abrir modal de editar cliente
        function abrirModalEditar(id, nombre, email, celular) {
            document.getElementById("modalTitulo").textContent = "Editar Cliente";
            document.getElementById("clienteId").value = id;
            document.getElementById("nombre").value = nombre;
            document.getElementById("email").value = email;
            document.getElementById("celular").value = celular;
            document.getElementById("modalEditar").style.display = "block";
        }

        // Función para cerrar modal
        function cerrarModal(modalId) {
            document.getElementById(modalId).style.display = "none";
        }

        // Función para eliminar cliente
        function eliminarCliente(id) {
            if (confirm("¿Estás seguro de que quieres eliminar este cliente?")) {
                fetch("deleteClient.php", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                        },
                        body: JSON.stringify({
                            id_cliente: id
                        })
                    })
                    .then(response => response.json())
                    .then(result => {
                        if (result.success) {
                            cargarClientes(); // Recargar la tabla
                            alert("Cliente eliminado exitosamente");
                        } else {
                            alert("Error al eliminar el cliente: " + (result.error || "Error desconocido"));
                        }
                    })
                    .catch(error => {
                        console.error("Error:", error);
                        alert("Error de conexión al eliminar");
                    });
            }
        }

        // Manejar el envío del formulario (para agregar/editar)
        document.getElementById("formCliente").addEventListener("submit", function(e) {
            e.preventDefault();

            const formData = {
                id_cliente: document.getElementById("clienteId").value,
                nombre: document.getElementById("nombre").value,
                email: document.getElementById("email").value,
                celular: document.getElementById("celular").value
            };

            const url = formData.id_cliente ? "updateClient.php" : "createClient.php";

            fetch(url, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify(formData)
                })
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        cerrarModal("modalEditar");
                        cargarClientes(); // Recargar la tabla
                        alert(formData.id_cliente ? "Cliente actualizado exitosamente" : "Cliente agregado exitosamente");
                    } else {
                        alert("Error: " + result.error);
                    }
                });
        });

        // Cargar clientes al iniciar la página
        document.addEventListener("DOMContentLoaded", cargarClientes);
    </script>

</body>

</html>