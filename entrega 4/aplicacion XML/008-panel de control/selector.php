<?php
$dir = "modelos/"; // Carpeta donde están los XML
$files = glob($dir . "*.xml"); // Busca archivos XML
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="estilo.css"> <!-- Enlace a la hoja de estilos -->
    <title>Seleccionar Archivo XML</title>
    <style>
        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            text-align: center;
        }
        .selector-container {
            margin-bottom: 20px;
        }
        select {
            padding: 10px;
            font-size: 16px;
            border: 2px solid #ffd700;
            background: #333;
            color: white;
            font-weight: bold;
            border-radius: 5px;
        }
        .title {
            position: absolute;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            text-align: center;
        }
        label{
            color:white;
        }
    </style>
</head>
<body>
    <h1 class="title">Selecciona un Archivo XML</h1>
    <div class="container">
        <div class="selector-container">
            <label for="fileSelect">Archivos disponibles:</label>
            <select name="f" id="fileSelect">
                <?php if (!$files): ?>
                    <option value="">No hay archivos disponibles</option>
                <?php else: ?>
                    <?php foreach ($files as $file): ?>
                        <?php $filename = basename($file, ".xml"); ?>
                        <option value="<?php echo $filename; ?>"><?php echo ucfirst($filename); ?></option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>
        <button onclick="submitForm()">Cargar Formulario</button>
    </div>
</body>
<script>
        function submitForm() {
            const select = document.getElementById('fileSelect');
            const selectedFile = select.value;
            if (!selectedFile) {
                alert("Por favor, selecciona un archivo XML.");
                return;
            }
            window.location.href = `index.html?f=${selectedFile}`;
        }
    </script>
</html>
