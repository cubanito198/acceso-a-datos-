<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Content-Type: application/json");

$servername = "localhost";
$username = "luisss";
$password = "luis";
$database = "crimsonn"; // Asegúrate de cambiar esto al nombre correcto de tu BD

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die(json_encode(["resultado" => "error", "mensaje" => "Error de conexión a la base de datos"]));
}

$operacion = isset($_GET['o']) ? $_GET['o'] : '';

if ($operacion == 'insertarCliente') {
    $nombre = isset($_POST['nombre']) ? $conn->real_escape_string($_POST['nombre']) : '';
    $apellidos = isset($_POST['apellidos']) ? $conn->real_escape_string($_POST['apellidos']) : '';
    $email = isset($_POST['email']) ? $conn->real_escape_string($_POST['email']) : '';
    $direccion = isset($_POST['direccion']) ? $conn->real_escape_string($_POST['direccion']) : '';
    $poblacion = isset($_POST['poblacion']) ? $conn->real_escape_string($_POST['poblacion']) : '';
    $cp = isset($_POST['cp']) ? $conn->real_escape_string($_POST['cp']) : '';
    $pais = isset($_POST['pais']) ? $conn->real_escape_string($_POST['pais']) : '';

    if (!empty($nombre) && !empty($apellidos) && !empty($email) && !empty($direccion) && !empty($poblacion) && !empty($cp) && !empty($pais)) {
        $sql = "INSERT INTO clientes (nombre, apellidos, email, direccion, poblacion, cp, pais) 
                VALUES ('$nombre', '$apellidos', '$email', '$direccion', '$poblacion', '$cp', '$pais')";
        
        if ($conn->query($sql) === TRUE) {
            echo json_encode(["resultado" => "ok", "mensaje" => "Cliente insertado correctamente", "redirect" => "clientes.html"]);
        } else {
            echo json_encode(["resultado" => "error", "mensaje" => "Error al insertar cliente: " . $conn->error]);
        }
    } else {
        echo json_encode(["resultado" => "error", "mensaje" => "Todos los campos son obligatorios"]);
    }


}elseif ($operacion == 'listarClientes') {
    $sql = "SELECT identificador, nombre, apellidos FROM clientes";
    $result = $conn->query($sql);

    if (!$result) {
        echo json_encode(["resultado" => "error", "mensaje" => "Error en la consulta SQL: " . $conn->error]);
        exit;
    }

    $clientes = [];
    while ($row = $result->fetch_assoc()) {
        $clientes[] = $row;
    }

    echo json_encode($clientes);
    exit;
}
elseif ($operacion == 'obtenerCliente') { 
    $identificador = isset($_GET['id']) ? intval($_GET['id']) : 0;

    if ($identificador > 0) {
        $sql = "SELECT * FROM clientes WHERE identificador = $identificador";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $cliente = $result->fetch_assoc();
            echo json_encode(["resultado" => "ok", "cliente" => $cliente]);
        } else {
            echo json_encode(["resultado" => "error", "mensaje" => "Cliente no encontrado"]);
        }
    } else {
        echo json_encode(["resultado" => "error", "mensaje" => "ID inválido"]);
    }
    exit;
}



$conn->close();
?>