<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/pagos.php';

if (!isset($_GET['id'])) {
    die('ID de pago no proporcionado');
}

$pagos = new Pagos($conn);
try {
    $pago = $pagos->getById($_GET['id']);
} catch(Exception $e) {
    die('Error al obtener el pago: ' . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recibo de Pago #<?= $pago['numero_recibo'] ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            font-size: 14px;
        }
        .recibo {
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
        .detalles {
            width: 100%;
            margin-bottom: 30px;
        }
        .detalles td {
            padding: 5px 10px;
        }
        .detalles .label {
            font-weight: bold;
            width: 150px;
        }
        .total {
            text-align: right;
            font-size: 18px;
            font-weight: bold;
            margin-top: 20px;
            border-top: 2px solid #000;
            padding-top: 10px;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 12px;
        }
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 100px;
            color: rgba(0,0,0,0.1);
            z-index: -1;
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
    <div class="recibo">
        <?php if ($pago['estado'] === 'Anulado'): ?>
            <div class="watermark">ANULADO</div>
        <?php endif; ?>

        <div class="header">
            <img src="<?= BASE_URL ?>assets/img/logo-clinica.png" alt="Logo Clínica" class="logo">
            <div class="titulo">RECIBO DE PAGO</div>
            <div class="info-clinica">
                Clínica Médica<br>
                RUC: 20123456789<br>
                Dirección: Av. Principal 123, Lima<br>
                Teléfono: (01) 123-4567
            </div>
        </div>

        <table class="detalles">
            <tr>
                <td class="label">N° de Recibo:</td>
                <td><?= $pago['numero_recibo'] ?></td>
                <td class="label">Fecha:</td>
                <td><?= date('d/m/Y H:i', strtotime($pago['fecha'])) ?></td>
            </tr>
            <tr>
                <td class="label">Paciente:</td>
                <td colspan="3"><?= $pago['nombre_paciente'] ?></td>
            </tr>
            <tr>
                <td class="label">Concepto:</td>
                <td colspan="3"><?= $pago['concepto'] ?></td>
            </tr>
            <tr>
                <td class="label">Método de Pago:</td>
                <td><?= $pago['metodo_pago'] ?></td>
                <td class="label">Estado:</td>
                <td><?= $pago['estado'] ?></td>
            </tr>
            <?php if (!empty($pago['observaciones'])): ?>
            <tr>
                <td class="label">Observaciones:</td>
                <td colspan="3"><?= $pago['observaciones'] ?></td>
            </tr>
            <?php endif; ?>
        </table>

        <div class="total">
            TOTAL: S/. <?= number_format($pago['monto'], 2) ?>
        </div>

        <div class="footer">
            Este documento no tiene validez tributaria.<br>
            Para cualquier consulta, por favor comuníquese con nosotros.
        </div>
    </div>

    <div class="no-print" style="text-align: center; margin-top: 20px;">
        <button onclick="window.print()">Imprimir Recibo</button>
        <button onclick="window.close()">Cerrar</button>
    </div>
</body>
</html> 