<?php include 'conexion.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Clientes — MEDPHARM</title>
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

        h2 {
            font-size: 24px;
            color: #4a3f35;
            margin: 30px 0 15px;
            font-weight: 500;
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

        input {
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

        input:focus {
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

        .btn-eliminar {
            color: #b55448;
            text-decoration: none;
            font-size: 14px;
            padding: 5px 10px;
            border-radius: 4px;
            transition: background-color 0.2s;
        }

        .btn-eliminar:hover {
            background-color: #f9ebea;
        }
    </style>
</head>
<body>

<div class="contenido">
    <h1>Gestión de Clientes</h1>
    <p class="subtitulo">Registro y consulta de clientes del sistema</p>

    <p class="estado-conexion">Conectado correctamente</p>

    <!-- FORMULARIO -->
    <div class="tarjeta">
        <h3>Registrar Nuevo Cliente</h3>
        <form method="POST">
            <div class="form-grid">
                <input type="text" name="nombre" placeholder="Nombre" required>
                <input type="text" name="apellido" placeholder="Apellido" required>
                <input type="text" name="telefono" placeholder="Teléfono">
                <input type="text" name="direccion" placeholder="Dirección">
                <input type="email" name="correo" placeholder="Correo electrónico">
                <button type="submit" name="guardar">Guardar Cliente</button>
            </div>
        </form>
    </div>

    <?php
    // Guardar cliente
    if (isset($_POST['guardar'])) {
        $nombre = trim($_POST['nombre']);
        $apellido = trim($_POST['apellido']);
        $telefono = trim($_POST['telefono'] ?? '');
        $direccion = trim($_POST['direccion'] ?? '');
        $correo = trim($_POST['correo'] ?? '');

        try {
            $sql = "INSERT INTO cliente (NOMBRE, APELLIDO, TELEFONO, DIRECCION, CORREO) 
                    VALUES (:nombre, :apellido, :telefono, :direccion, :correo)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':apellido', $apellido);
            $stmt->bindParam(':telefono', $telefono);
            $stmt->bindParam(':direccion', $direccion);
            $stmt->bindParam(':correo', $correo);
            $stmt->execute();
            echo "<p class='mensaje exito'>✅ Cliente registrado correctamente</p>";
        } catch (PDOException $e) {
            echo "<p class='mensaje error'>❌ Error: " . $e->getMessage() . "</p>";
        }
    }

    // Eliminar cliente
    if (isset($_GET['eliminar'])) {
        $id = $_GET['eliminar'];
        try {
            $sql = "DELETE FROM cliente WHERE idCLIENTE = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            header("Location: clientes.php");
            exit;
        } catch (PDOException $e) {
            echo "<p class='mensaje error'>❌ Error al eliminar: " . $e->getMessage() . "</p>";
        }
    }

    // Cargar lista de clientes
    try {
        $sql = "SELECT * FROM cliente ORDER BY idCLIENTE DESC";
        $stmt = $pdo->query($sql);
        $clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "<p class='mensaje error'>❌ Error al cargar clientes: " . $e->getMessage() . "</p>";
        $clientes = [];
    }
    ?>

    <!-- TABLA DE CLIENTES -->
    <h3>Lista de Clientes</h3>
    <table>
        <thead>
            <tr>
                <th>Nombre Completo</th>
                <th>Teléfono</th>
                <th>Dirección</th>
                <th>Correo</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($clientes as $c): ?>
            <tr>
                <td><?= htmlspecialchars($c['NOMBRE']) ?> <?= htmlspecialchars($c['APELLIDO']) ?></td>
                <td><?= htmlspecialchars($c['TELEFONO'] ?? '') ?></td>
                <td><?= htmlspecialchars($c['DIRECCION'] ?? '') ?></td>
                <td><?= htmlspecialchars($c['CORREO'] ?? '') ?></td>
                <td>
                    <a href="clientes.php?eliminar=<?= $c['idCLIENTE'] ?>" 
                       class="btn-eliminar" 
                       onclick="return confirm('¿Seguro que deseas eliminar este cliente?')">Eliminar</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

</body>
</html>