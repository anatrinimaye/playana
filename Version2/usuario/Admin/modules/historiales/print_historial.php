<?php
include '../../config/conexion.php';


if (!isset($_GET['id'])) {
    die('ID de historial no proporcionado');
}

$historiales = new Historiales($conn);
try {
    $historial = $historiales->getById($_GET['id']);
} catch(Exception $e) {
    die('Error al obtener el historial: ' . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Historial Médico - <?= $historial['nombre_paciente'] ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            font-size: 12px;
        }
        .historial {
            max-width: 800px;
            margin: 0 auto;
            border: 1px solid #ccc;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo {
            max-width: 200px;
            margin-bottom: 10px;
        }
        .titulo {
            font-size: 24px;
            font-weight: bold;
            margin: 10px 0;
        }
        .info-clinica {
            font-size: 12px;
            margin-bottom: 20px;
        }
        .info-paciente {
            margin-bottom: 20px;
            border: 1px solid #ddd;
            padding: 10px;
            background-color: #f9f9f9;
        }
        .seccion {
            margin-bottom: 20px;
        }
        .seccion h3 {
            background-color: #f0f0f0;
            padding: 5px 10px;
            margin: 0 0 10px 0;
            font-size: 14px;
        }
        .seccion-contenido {
            padding: 0 10px;
        }
        .firma {
            margin-top: 50px;
            text-align: center;
        }
        .linea-firma {
            width: 200px;
            margin: 0 auto;
            border-top: 1px solid #000;
            padding-top: 5px;
        }
        @media print {
            body {
                padding: 0;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="historial">
        <div class="header">
            <img src="<?= BASE_URL ?>assets/img/logo-clinica.png" alt="Logo Clínica" class="logo">
            <div class="titulo">HISTORIAL MÉDICO</div>
            <div class="info-clinica">
                Clínica Médica<br>
                RUC: 20123456789<br>
                Dirección: Av. Principal 123, Lima<br>
                Teléfono: (01) 123-4567
            </div>
        </div>

        <div class="info-paciente">
            <strong>Fecha:</strong> <?= date('d/m/Y H:i', strtotime($historial['fecha'])) ?><br>
            <strong>Paciente:</strong> <?= $historial['nombre_paciente'] ?><br>
            <strong>Médico:</strong> <?= $historial['nombre_medico'] ?>
        </div>

        <div class="seccion">
            <h3>Motivo de Consulta</h3>
            <div class="seccion-contenido">
                <?= nl2br($historial['motivo_consulta']) ?>
            </div>
        </div>

        <?php if (!empty($historial['antecedentes'])): ?>
        <div class="seccion">
            <h3>Antecedentes</h3>
            <div class="seccion-contenido">
                <?= nl2br($historial['antecedentes']) ?>
            </div>
        </div>
        <?php endif; ?>

        <div class="seccion">
            <h3>Examen Físico</h3>
            <div class="seccion-contenido">
                <?= nl2br($historial['examen_fisico']) ?>
            </div>
        </div>

        <div class="seccion">
            <h3>Diagnóstico</h3>
            <div class="seccion-contenido">
                <?= nl2br($historial['diagnostico']) ?>
            </div>
        </div>

        <div class="seccion">
            <h3>Tratamiento</h3>
            <div class="seccion-contenido">
                <?= nl2br($historial['tratamiento']) ?>
            </div>
        </div>

        <?php if (!empty($historial['observaciones'])): ?>
        <div class="seccion">
            <h3>Observaciones</h3>
            <div class="seccion-contenido">
                <?= nl2br($historial['observaciones']) ?>
            </div>
        </div>
        <?php endif; ?>

        <div class="firma">
            <div class="linea-firma">
                <?= $historial['nombre_medico'] ?><br>
                CMP: XXXXX
            </div>
        </div>
    </div>

    <div class="no-print" style="text-align: center; margin-top: 20px;">
        <button onclick="window.print()">Imprimir Historial</button>
        <button onclick="window.close()">Cerrar</button>
    </div>
</body>
</html> 