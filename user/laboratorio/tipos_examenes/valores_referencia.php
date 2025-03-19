<?php
session_start();
include '../../config/conexion.php';

// Verificar si el usuario está logueado y es personal de laboratorio
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'laboratorio') {
    header('Location: ../../login.php');
    exit();
}

if (!isset($_GET['tipo_id'])) {
    $_SESSION['error'] = "ID de tipo de examen no proporcionado";
    header('Location: gestionar_tipos.php');
    exit();
}

$tipo_id = intval($_GET['tipo_id']);

// Obtener información del tipo de examen
$query = "SELECT * FROM tipos_examenes WHERE id = $tipo_id";
$resultado = mysqli_query($conexion, $query);

if (!($tipo = mysqli_fetch_assoc($resultado))) {
    $_SESSION['error'] = "Tipo de examen no encontrado";
    header('Location: gestionar_tipos.php');
    exit();
}

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Eliminar valores anteriores
    mysqli_query($conexion, "DELETE FROM valores_referencia WHERE tipo_examen_id = $tipo_id");
    
    // Insertar nuevos valores
    if (isset($_POST['parametros']) && is_array($_POST['parametros'])) {
        foreach ($_POST['parametros'] as $i => $parametro) {
            if (!empty($parametro)) {
                $unidad = mysqli_real_escape_string($conexion, $_POST['unidades'][$i]);
                $min = mysqli_real_escape_string($conexion, $_POST['valores_minimos'][$i]);
                $max = mysqli_real_escape_string($conexion, $_POST['valores_maximos'][$i]);
                $texto = mysqli_real_escape_string($conexion, $_POST['valores_texto'][$i]);
                $genero = mysqli_real_escape_string($conexion, $_POST['generos'][$i]);
                $edad_min = !empty($_POST['edades_minimas'][$i]) ? intval($_POST['edades_minimas'][$i]) : 'NULL';
                $edad_max = !empty($_POST['edades_maximas'][$i]) ? intval($_POST['edades_maximas'][$i]) : 'NULL';
                
                $query = "INSERT INTO valores_referencia (
                    tipo_examen_id, parametro, unidad, valor_minimo,
                    valor_maximo, valor_texto, genero, edad_minima, edad_maxima
                ) VALUES (
                    $tipo_id, '$parametro', '$unidad', '$min',
                    '$max', '$texto', '$genero', $edad_min, $edad_max
                )";
                mysqli_query($conexion, $query);
            }
        }
        $_SESSION['mensaje'] = "Valores de referencia actualizados correctamente";
    }
    
    header('Location: ' . $_SERVER['PHP_SELF'] . '?tipo_id=' . $tipo_id);
    exit();
}

// Obtener valores de referencia actuales
$query = "SELECT * FROM valores_referencia WHERE tipo_examen_id = $tipo_id ORDER BY id";
$valores = mysqli_query($conexion, $query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Valores de Referencia</title>
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4>Valores de Referencia - <?php echo htmlspecialchars($tipo['nombre']); ?></h4>
                <a href="gestionar_tipos.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
            <div class="card-body">
                <?php if (isset($_SESSION['mensaje'])): ?>
                    <div class="alert alert-success"><?php echo $_SESSION['mensaje']; unset($_SESSION['mensaje']); ?></div>
                <?php endif; ?>
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
                <?php endif; ?>

                <form method="POST">
                    <div id="valoresContainer">
                        <?php while ($valor = mysqli_fetch_assoc($valores)): ?>
                            <div class="border p-3 mb-3">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-2">
                                            <label>Parámetro</label>
                                            <input type="text" class="form-control" name="parametros[]" 
                                                value="<?php echo htmlspecialchars($valor['parametro']); ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-2">
                                            <label>Unidad</label>
                                            <input type="text" class="form-control" name="unidades[]"
                                                value="<?php echo htmlspecialchars($valor['unidad']); ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-2">
                                            <label>Valor Mínimo</label>
                                            <input type="text" class="form-control" name="valores_minimos[]"
                                                value="<?php echo htmlspecialchars($valor['valor_minimo']); ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-2">
                                            <label>Valor Máximo</label>
                                            <input type="text" class="form-control" name="valores_maximos[]"
                                                value="<?php echo htmlspecialchars($valor['valor_maximo']); ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-2">
                                            <label>Valor Texto</label>
                                            <input type="text" class="form-control" name="valores_texto[]"
                                                value="<?php echo htmlspecialchars($valor['valor_texto']); ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-2">
                                            <label>Género</label>
                                            <select class="form-select" name="generos[]">
                                                <option value="ambos" <?php echo $valor['genero'] === 'ambos' ? 'selected' : ''; ?>>Ambos</option>
                                                <option value="masculino" <?php echo $valor['genero'] === 'masculino' ? 'selected' : ''; ?>>Masculino</option>
                                                <option value="femenino" <?php echo $valor['genero'] === 'femenino' ? 'selected' : ''; ?>>Femenino</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-2">
                                            <label>Edad Mínima</label>
                                            <input type="number" class="form-control" name="edades_minimas[]"
                                                value="<?php echo $valor['edad_minima']; ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-2">
                                            <label>Edad Máxima</label>
                                            <input type="number" class="form-control" name="edades_maximas[]"
                                                value="<?php echo $valor['edad_maxima']; ?>">
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-danger btn-sm mt-2" onclick="this.parentElement.remove()">
                                    <i class="fas fa-trash"></i> Eliminar
                                </button>
                            </div>
                        <?php endwhile; ?>
                    </div>

                    <button type="button" class="btn btn-secondary" onclick="agregarValor()">
                        <i class="fas fa-plus"></i> Agregar Valor de Referencia
                    </button>

                    <hr>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Guardar Cambios
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function agregarValor() {
            const container = document.getElementById('valoresContainer');
            const template = `
                <div class="border p-3 mb-3">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-2">
                                <label>Parámetro</label>
                                <input type="text" class="form-control" name="parametros[]" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-2">
                                <label>Unidad</label>
                                <input type="text" class="form-control" name="unidades[]">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-2">
                                <label>Valor Mínimo</label>
                                <input type="text" class="form-control" name="valores_minimos[]">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-2">
                                <label>Valor Máximo</label>
                                <input type="text" class="form-control" name="valores_maximos[]">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-2">
                                <label>Valor Texto</label>
                                <input type="text" class="form-control" name="valores_texto[]">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-2">
                                <label>Género</label>
                                <select class="form-select" name="generos[]">
                                    <option value="ambos">Ambos</option>
                                    <option value="masculino">Masculino</option>
                                    <option value="femenino">Femenino</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-2">
                                <label>Edad Mínima</label>
                                <input type="number" class="form-control" name="edades_minimas[]">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-2">
                                <label>Edad Máxima</label>
                                <input type="number" class="form-control" name="edades_maximas[]">
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-danger btn-sm mt-2" onclick="this.parentElement.remove()">
                        <i class="fas fa-trash"></i> Eliminar
                    </button>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', template);
        }
    </script>
</body>
</html>