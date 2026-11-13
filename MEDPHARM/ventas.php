<?php include 'conexion.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Ventas — MEDPHARM</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Garamond, serif;
        }

        body {
            background-color: #f9f6f0;
            color: #4a3f35;
            padding: 30px;
            line-height: 1.6;
        }

        .contenido {
            max-width: 1200px;
            margin: 0 auto;
        }

        h1 {
            font-size: 32px;
            color: #3a3228;
            margin-bottom: 5px;
            font-weight: 600;
        }

        .subtitulo {
            color: #7a6f62;
            font-size: 16px;
            margin-bottom: 30px;
        }

        .estado-conexion {
            color: #2f855a;
            font-size: 15px;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .estado-conexion::before {
            content: "✓";
            border: 1px solid #2f855a;
            border-radius: 4px;
            width: 18px;
            height: 18px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
        }

        .tarjeta {
            background-color: #fffbf5;
            border: 1px solid #e8ddd0;
            border-radius: 16px;
            padding: 25px 30px;
            margin-bottom: 30px;
        }

        h3 {
            font-size: 18px;
            color: #4a3f35;
            margin-bottom: 20px;
            font-weight: 500;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 15px;
            align-items: end;
        }

        label {
            display: block;
            font-size: 14px;
            color: #6a5f52;
            margin-bottom: 6px;
        }

        input, select {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid #d9cdbd;
            border-radius: 8px;
            background-color: #fffef9;
            font-size: 15px;
            color: #4a3f35;
        }

        input::placeholder {
            color: #9a8f82;
        }

        input:focus, select:focus {
            outline: none;
            border-color: #b8a896;
        }

        button {
            padding: 10px 24px;
            background-color: #8b7355;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        button:hover {
            background-color: #705c44;
        }

        .mensaje {
            padding: 12px 16px;
            border-radius: 8px;
            margin: 15px 0;
            font-size: 15px;
        }

        .mensaje.exito {
            background-color: #f0f7ee;
            color: #2f6835;
            border: 1px solid #cde6cb;
        }

        .mensaje.error {
            background-color: #fbf0f0;
            color: #9c3c3c;
            border: 1px solid #f0d0d0;
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid #e8ddd0;
            background-color: #fffbf5;
        }

        th {
            background-color: #f0e7da;
            color: #4a3f35;
            padding: 14px 18px;
            text-align: left;
            font-weight: 600;
            font-size: 15px;
        }

        td {
            padding: 14px 18px;
            border-top: 1px solid #e8ddd0;
            color: #4a3f35;
            font-size: 15px;
        }

        tr:hover td {
            background-color: #fffaf2;
        }
    </style>
</head>
<body>

<div class="contenido">
    <h1>💰 Registro de Ventas</h1>
    <p class="subtitulo">Historial y registro de transacciones</p>

    <p class="estado-conexion">Conectado correctamente</p>

    <!-- FORMULARIO DE VENTA -->
    <div class="tarjeta">
        <h3>Registrar Nueva Venta</h3>
        <form method="POST">
            <div class="form-grid">
                <div>
                    <label>Cliente:</label>
                    <select name="id_cliente" required>
                        <option value="">Seleccionar cliente</option>
                        <?php
                        try {
                            $stmt = $pdo->query("SELECT * FROM cliente ORDER BY NOMBRE");
                            while ($c = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                echo "<option value='{$c['idCLIENTE']}'>{$c['NOMBRE']} {$c['APELLIDO']}</option>";
                            }
                        } catch (PDOException $e) {
                            echo "<option value=''>Error al cargar clientes</option>";
                        }
                        ?>
                    </select>
                </div>
                <div>
                    <label>ID Lote/Producto:</label>
                    <input type="text" name="id_lote" placeholder="Ingrese el ID del lote" required>
                </div>
                <div>
                    <label>Cantidad:</label>
                    <input type="number" name="cantidad" min="1" required>
                </div>
                <div>
                    <label>Total de la venta ($):</label>
                    <input type="number" step="0.01" name="total" placeholder="0.00" required>
                </div>
                <button type="submit" name="vender">Registrar Venta</button>
            </div>
        </form>
    </div>

    <?php
    if (isset($_POST['vender'])) {
        $id_cliente = $_POST['id_cliente'];
        $id_lote = $_POST['id_lote'];
        $cantidad = $_POST['cantidad'];
        $total = $_POST['total'];
        $fecha = date('Y-m-d H:i:s');
        $estado = 'Completada';

        try {
            $pdo->beginTransaction();
            
            // Insertar venta principal
            $sql = "INSERT INTO venta (FECHA, TOTAL, CANTIDAD, ESTADO, id_cliente) 
                    VALUES (:fecha, :total, :cantidad, :estado, :id_cliente)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':fecha', $fecha);
            $stmt->bindParam(':total', $total);
            $stmt->bindParam(':cantidad', $cantidad);
            $stmt->bindParam(':estado', $estado);
            $stmt->bindParam(':id_cliente', $id_cliente);
            $stmt->execute();
            
            $id_venta = $pdo->lastInsertId();
            
            // Insertar detalle de la venta
            $sql_det = "INSERT INTO detalle_venta (ID_VENTA, ID_LOTE, CANTIDAD) 
                        VALUES (:id_venta, :id_lote, :cantidad)";
            $stmt_det = $pdo->prepare($sql_det);
            $stmt_det->bindParam(':id_venta', $id_venta);
            $stmt_det->bindParam(':id_lote', $id_lote);
            $stmt_det->bindParam(':cantidad', $cantidad);
            $stmt_det->execute();
            
            $pdo->commit();
            echo "<p class='mensaje exito'>✅ Venta registrada con éxito. N° Venta: $id_venta</p>";
        } catch (PDOException $e) {
            $pdo->rollBack();
            echo "<p class='mensaje error'>❌ Error: " . $e->getMessage() . "</p>";
        }
    }
    ?>

    <!-- HISTORIAL DE VENTAS -->
    <div class="tarjeta">
        <h3>Historial de Ventas</h3>
        <table>
            <thead>
                <tr>
                    <th>N° Venta</th>
                    <th>Fecha</th>
                    <th>Cliente</th>
                    <th>Cantidad</th>
                    <th>Total</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
            <?php
            try {
                $sql = "SELECT v.*, CONCAT(c.NOMBRE, ' ', c.APELLIDO) AS cliente 
                        FROM venta v 
                        LEFT JOIN cliente c ON v.id_cliente = c.idCLIENTE 
                        ORDER BY v.idVENTA DESC";
                $stmt = $pdo->query($sql);
                while ($v = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr>";
                    echo "<td>#{$v['idVENTA']}</td>";
                    echo "<td>{$v['FECHA']}</td>";
                    echo "<td>{$v['cliente']}</td>";
                    echo "<td>{$v['CANTIDAD']}</td>";
                    echo "<td>$" . number_format($v['TOTAL'], 2) . "</td>";
                    echo "<td>{$v['ESTADO']}</td>";
                    echo "</tr>";
                }
            } catch (PDOException $e) {
                echo "<tr><td colspan='6' style='color:red;'>Error al cargar ventas: " . $e->getMessage() . "</td></tr>";
            }
            ?>
            </tbody>
        </table>
    </div>

    <!-- DETALLE DE VENTAS -->
    <div class="tarjeta">
        <h3>Detalle de Ventas</h3>
        <table>
            <thead>
                <tr>
                    <th>ID Detalle</th>
                    <th>N° Venta</th>
                    <th>ID Lote</th>
                    <th>Cantidad</th>
                </tr>
            </thead>
            <tbody>
            <?php
            try {
                $sql = "SELECT * FROM detalle_venta ORDER BY ID_DETALLE DESC";
                $stmt = $pdo->query($sql);
                while ($d = $stmt->fetch(PDO::FETCH_ASSOC)) {
            ?>
            <tr>
                <td><?= $d['ID_DETALLE'] ?></td>
                <td><?= $d['ID_VENTA'] ?></td>
                <td><?= htmlspecialchars($d['ID_LOTE']) ?></td>
                <td><?= $d['CANTIDAD'] ?></td>
            </tr>
            <?php
                }
            } catch (PDOException $e) {
                echo "<tr><td colspan='4' style='color:red;'>Error: " . $e->getMessage() . "</td></tr>";
            }
            ?>
            </tbody>
        </table>
    </div>

</div>

</body>
</html>