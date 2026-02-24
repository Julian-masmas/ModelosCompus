<?php
// Conexión a la base de datos
$conn = new mysqli("localhost", "echaux", "yQ]iYiO_O)D.ig7q", "echaux");

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$sql = "SELECT name, code, adress, age FROM bienestar";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Bienestar</title>
    <style>
        table { border-collapse: collapse; width: 80%; margin: 20px auto; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: center; }
    </style>
</head>
<body>
    <h2 style="text-align:center;">Datos de Bienestar</h2>
	<div class="buscador">
    	<input type="text" placeholder="Buscar...">
    	<button>🔍</button>
    	<button>➕ Agregar</button>
  	</div>

    <table>
        <tr>
            <th>Nombre</th>
            <th>Código</th>
            <th>Dirección</th>
            <th>Edad</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>".$row["name"]." </td>
                        <td>".$row["code"]."  </td>
                        <td>".$row["adress"]." </td>
                        <td>".$row["age"]."  </td>
						<td class='acciones'>
							<button>✏️</button>
							<button>🗑️</button>
						</td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='4'>No hay datos</td></tr>";
        }
        $conn->close();
        ?>
    </table>
	<div class="navegacion">
    	<button>⏮️</button>
    	<button>◀️</button>
    	<button>▶️</button>
    	<button>⏭️</button>
  </div>
</body>
</html>