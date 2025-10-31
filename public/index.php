<?php 
    session_status() == PHP_SESSION_NONE ? session_start() : '';
    $options = require_once '../components/options.php';
    
    $weather = $_SESSION['weather'] ?? null;
    $error = $_SESSION['weather_error'] ?? null;

    unset($_SESSION['weather'], $_SESSION['weather_error']);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Formulário de Localização</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="/css/style.css">
</head>
<body>
  <div class="form-container">
    <h1>Localização</h1>
    <form method="GET" action="./process.php">
        <label for="cidade">Cidade</label>
        <input type="text" id="cidade" name="cidade" placeholder="Digite sua cidade" required />

        <label for="pais">País (Código ISO)</label>
        <select id="pais" name="pais" required>
            <?php foreach($options as $option) echo $option; ?>
        </select>

        <button type="submit">Enviar</button>
    </form>

    <?php if(isset($weather)): ?>
        <?php if(isset($weather['error'])): ?>
            <div class="error"><?= $weather['error'] ?></div>
        <?php else: ?>
            <div class="weather">
                <h2><?= $weather['name'] ?>, <?= strtoupper($weather['sys']['country']) ?></h2>
                <img src="http://openweathermap.org/img/wn/<?= $weather['weather'][0]['icon'] ?>@2x.png" alt="Ícone do clima">
                <p><strong><?= round($weather['main']['temp']) ?>°C</strong></p>
                <p><?= ucfirst($weather['weather'][0]['description']) ?></p>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>
</body>
</html>
