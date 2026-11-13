<?php include 'conexion.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Usuarios — MEDPHARM</title>
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
    <h1>👤 Gestión de Usuarios</h1>
    <p class="subtitulo">Administración de accesos y perfiles del sistema</p>

    <p class="estado-conexion">Conectado correctamente</p>

    <!-- FORMULARIO -->
    <div class="tarjeta">
        <h3>Registrar Nuevo Usuario</h3>
        <form method="POST">
            <div class="form-grid">
                <input type="text" name="nombre" placeholder="Nombre" required>
                <input type="text" name="apellido" placeholder="Apellido" required>
                <input type="email" name="correo" placeholder="Correo electrónico" required>
                <input type="text" name="usuario" placeholder="Usuario" required>
                <input type="password" name="contrasena" placeholder="Contraseña" required>
                <input type="text" name="telefono" placeholder="Teléfono">
                <select name="rol" required>
                    <option value="">Seleccionar rol</option>
                    <option value="Administrador">Administrador</option>
                    <option value="Empleado">Empleado</option>
                </select>
                <button type="submit" name="guardar">Guardar Usuario</button>
            </div>
        </form>
    </div>

    <?php
    // HU-USU-01: Registrar nuevo usuario
    if (isset($_POST['guardar'])) {
        $nombre = trim($_POST['nombre']);
        $apellido = trim($_POST['apellido']);
        $correo = trim($_POST['correo']);
        $usuario = trim($_POST['usuario']);
        $contrasena = trim($_POST['contrasena']);
        $telefono = trim($_POST['telefono'] ?? '');
        $rol = trim($_POST['rol']);

        try {
            $sql = "INSERT INTO usuario (NOMBRE, APELLIDO, CORREO, USUARIO, CONTRASEÑA, TELEFONO, ROL) 
                    VALUES (:nombre, :apellido, :correo, :usuario, :contrasena, :telefono, :rol)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':apellido', $apellido);
            $stmt->bindParam(':correo', $correo);
            $stmt->bindParam(':usuario', $usuario);
            $stmt->bindParam(':contrasena', $contrasena);
            $stmt->bindParam(':telefono', $telefono);
            $stmt->bindParam(':rol', $rol);
            $stmt->execute();
            echo "<p class='mensaje exito'>✅ Usuario registrado correctamente</p>";
        } catch (PDOException $e) {
            echo "<p class='mensaje error'>❌ Error: " . $e->getMessage() . "</p>";
        }
    }

    // HU-USU-03: Eliminar usuario
    if (isset($_GET['eliminar'])) {
        $id = $_GET['eliminar'];
        try {
            $sql = "DELETE FROM usuario WHERE id_usuario = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            header("Location: usuarios.php");
            exit;
        } catch (PDOException $e) {
            echo "<p class='mensaje error'>❌ Error al eliminar: " . $e->getMessage() . "</p>";
        }
    }

    // HU-USU-02: Listar todos los usuarios
    try {
        $sql = "SELECT * FROM usuario ORDER BY id_usuario DESC";
        $stmt = $pdo->query($sql);
        $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "<p class='mensaje error'>❌ Error al cargar usuarios: " . $e->getMessage() . "</p>";
        $usuarios = [];
    }
    ?>

    <!-- TABLA DE USUARIOS -->
    <h3>Lista de Usuarios</h3>
    <table>
        <thead>
            <tr>
                <th>Nombre Completo</th>
                <th>Correo</th>
                <th>Usuario</th>
                <th>Teléfono</th>
                <th>Rol</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($usuarios as $u): ?>
            <tr>
                <td><?= htmlspecialchars($u['NOMBRE']) ?> <?= htmlspecialchars($u['APELLIDO']) ?></td>
                <td><?= htmlspecialchars($u['CORREO']) ?></td>
                <td><?= htmlspecialchars($u['USUARIO'] ?? '') ?></td>
                <td><?= htmlspecialchars($u['TELEFONO'] ?? '') ?></td>
                <td><?= htmlspecialchars($u['ROL']) ?></td>
                <td>
                    <a href="usuarios.php?eliminar=<?= $u['id_usuario'] ?>" 
                       class="btn-eliminar" 
                       onclick="return confirm('¿Seguro que deseas eliminar este usuario?')">Eliminar</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

</body>
</html>