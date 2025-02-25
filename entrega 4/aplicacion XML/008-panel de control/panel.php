<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>XML Control Panel</title>
    <style>
        /* Estilos generales del cuerpo */
        /* Estilos generales con estética de rally */
body {
    font-family: 'Arial', sans-serif;
    background: #1c1c1c; /* Fondo oscuro tipo asfalto */
    background-image: url('https://www.transparenttextures.com/patterns/carbon-fibre.png'); /* Textura fibra de carbono */
    color: #fff;
    margin: 0;
    padding: 20px;
}

/* Título principal con efecto deportivo */
h1 {
    text-align: center;
    color: #ffcc00; /* Amarillo rally */
    text-transform: uppercase;
    font-size: 2rem;
    font-weight: bold;
    text-shadow: 3px 3px 10px rgba(255, 204, 0, 0.8);
}

/* Estilos para carpetas */
.folder {
    margin-bottom: 20px;
    padding: 15px;
    border: 2px solid #ff0000; /* Rojo agresivo */
    background: rgba(255, 0, 0, 0.1);
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(255, 0, 0, 0.6);
}

/* Título de carpeta */
.folder h2 {
    margin-bottom: 10px;
    color: #ffcc00;
    text-transform: uppercase;
}

/* Lista de archivos */
.file-list {
    list-style-type: none;
    padding: 0;
}

.file-list li {
    padding: 8px 0;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 2px solid rgba(255, 204, 0, 0.5);
    transition: all 0.3s ease-in-out;
}

.file-list li:hover {
    background: rgba(255, 0, 0, 0.2);
}

/* Botones con estilo racing */
button {
    background: #ff0000;
    color: white;
    border: 2px solid #ffcc00;
    border-radius: 6px;
    padding: 8px 12px;
    cursor: pointer;
    font-size: 14px;
    font-weight: bold;
    text-transform: uppercase;
    transition: all 0.3s ease-in-out;
}

button:hover {
    background: #d40000;
    box-shadow: 0 0 10px rgba(255, 0, 0, 0.7);
}

/* Modal (ventana emergente) con diseño agresivo */
.modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.8);
    justify-content: center;
    align-items: center;
    z-index: 1000;
}

/* Contenido del modal */
.modal-content {
    background: #2a2a2a;
    padding: 20px;
    border-radius: 8px;
    width: 80%;
    max-height: 80%;
    overflow-y: auto;
    box-shadow: 0 0 15px rgba(255, 204, 0, 0.5);
}

/* Contenido de archivo XML dentro del modal */
.modal-content pre {
    font-family: monospace;
    background-color: #333;
    padding: 10px;
    border: 2px solid #ffcc00;
    border-radius: 6px;
    color: #fff;
}

/* Botón para cerrar el modal */
.close-btn {
    display: block;
    margin-left: auto;
    background: #ff0000;
    padding: 8px 12px;
    font-weight: bold;
    text-transform: uppercase;
}

.close-btn:hover {
    background: #a71d2a;
}

/* Responsive Design */
@media (max-width: 768px) {
    h1 {
        font-size: 1.5rem;
    }

    .modal-content {
        width: 90%;
    }

    button {
        font-size: 12px;
    }
}

@media (max-width: 480px) {
    h1 {
        font-size: 1.3rem;
    }

    button {
        padding: 6px 10px;
        font-size: 10px;
    }
}

    </style>
</head>
<body>
    <h1>XML Control Panel</h1>

    <?php
    /**
     * Función recursiva que escanea un directorio en busca de archivos y carpetas.
     * Muestra la estructura del directorio en formato HTML.
     * 
     * @param string $baseDir Ruta del directorio a escanear
     */
    function parseDirectory($baseDir)
    {
        // Obtener lista de archivos y carpetas en el directorio
        $items = scandir($baseDir);
        echo "<ul class='file-list'>";

        foreach ($items as $item) {
            // Ignorar los directorios especiales "." y ".."
            if ($item === '.' || $item === '..') {
                continue;
            }

            $fullPath = $baseDir . '/' . $item; // Ruta completa del archivo/carpeta

            if (is_dir($fullPath)) {
                // Si es un directorio, mostrarlo y llamar recursivamente la función
                echo "<div class='folder'>";
                echo "<h2>Folder: $item</h2>";
                parseDirectory($fullPath);
                echo "</div>";
            } elseif (pathinfo($fullPath, PATHINFO_EXTENSION) === 'xml') {
                // Si es un archivo XML, agregarlo a la lista con botones de acciones
                echo "<li>
                        $item 
                        <button onclick=\"viewContent('$fullPath')\">View</button>
                        <button onclick=\"downloadFile('$fullPath')\">Download</button>
                      </li>";
            }
        }

        echo "</ul>";
    }

    $baseDir = 'xml'; // Carpeta donde se almacenan los archivos XML

    // Verificar si la carpeta existe antes de intentar leerla
    if (!is_dir($baseDir)) {
        echo "<p>XML base directory does not exist.</p>";
        exit;
    }

    // Llamar a la función para escanear el directorio base
    parseDirectory($baseDir);
    ?>

    <!-- Modal para ver archivos XML -->
    <div id="contentModal" class="modal">
        <div class="modal-content">
            <button class="close-btn" onclick="closeModal()">Close</button>
            <pre id="contentViewer"></pre>
        </div>
    </div>

    <script>
        /**
         * Carga y muestra el contenido de un archivo XML en un modal.
         * @param {string} filePath - Ruta del archivo a cargar.
         */
        function viewContent(filePath) {
    console.log("File path received:", filePath); // <-- Agregar para depurar

    if (!filePath || filePath === "null") {
        alert("Error: No file path provided.");
        return;
    }

    fetch(filePath)
        .then(response => {
            if (!response.ok) throw new Error('Failed to fetch file content.');
            return response.text();
        })
        .then(content => {
            document.getElementById('contentViewer').textContent = content;
            document.getElementById('contentModal').style.display = 'flex';
        })
        .catch(error => {
            alert('Error loading file content: ' + error.message);
        });
}


        /**
         * Cierra el modal de visualización de archivos.
         */
        function closeModal() {
            document.getElementById('contentModal').style.display = 'none';
        }

        /**
         * NUEVA FUNCIONXW
         * Descarga un archivo XML desde el servidor.
         * @param {string} filePath - Ruta del archivo a descargar.
         */
        function downloadFile(filePath) {
            const link = document.createElement('a');
            link.href = filePath;
            link.download = filePath.split('/').pop(); // Extraer el nombre del archivo
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
        
    </script>
</body>
</html>
