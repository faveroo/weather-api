<?php 
session_status() == PHP_SESSION_NONE ? session_start() : '';
setlocale(LC_TIME, 'pt_BR.utf8', 'pt_BR', 'Portuguese_Brazil');

$options = require_once '../components/options.php';

$weather = $_SESSION['weather'] ?? null;
$previsions = $_SESSION['previsions'] ?? null;
$error = $_SESSION['weather_error'] ?? null;
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Previsão do Tempo</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/css/style.css">
</head>
<body>
  <div class="parent">
    <!-- Formulário -->
    <div class="form-container">
      <h1 class="text-center mb-4">Localização</h1>

      <form method="GET" action="./process.php">

          <div class="mb-3">
            <label for="pais" class="form-label">País (Código ISO): </label>
            <select id="pais" name="pais" class="form-select" required>
                        <option value="">Selecione um País</option>
                <?php foreach($options as $option): ?>
                        <option value="<?= $option['iso3'] ?>"><?= $option['country'] ?></option>
                <?php endforeach; ?>
            </select>
          </div>

        <div class="mb-3">
          <label for="cidade" class="form-label">Cidade: </label>
          <select name="cidade" id="cidade" class="form-select" required>
                <option value="">Selection uma cidade</option>            
          </select>
        </div>


        <button type="submit" class="btn btn-dark w-100">Enviar</button>
      </form>

      <?php if(isset($weather)): ?>
        <?php if(isset($weather['error'])): ?>
          <div class="error mt-3"><?= $weather['error'] ?></div>
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

    <!-- Previsões -->
    <?php if(isset($previsions)): ?>
      <div class="previsions">
        <?php foreach($previsions as $index => $prevision): ?>
          <div class="prevision" data-bs-toggle="collapse" data-bs-target="#details<?= $index ?>" aria-expanded="false">
            <div class="summary">
              <p class="fw-bold mb-1"><?= date('l H:i', $prevision['dt']) ?></p>
              <img src="http://openweathermap.org/img/wn/<?= $prevision['weather'][0]['icon'] ?>@2x.png" alt="">
              <p class="fs-5 mb-0"><?= round($prevision['main']['temp']) ?>°C</p>
              <p class="text-muted mb-0"><?= ucfirst($prevision['weather'][0]['description']) ?></p>
            </div>
            <div id="details<?= $index ?>" class="collapse">
              <div class="p-3 border-top">
                <p class="mb-1">Sensação térmica: <?= round($prevision['main']['feels_like']) ?>°C</p>
                <p class="mb-1">Umidade: <?= $prevision['main']['humidity'] ?>%</p>
                <p class="mb-1">Vento: <?= round($prevision['wind']['speed'] * 3.6, 1) ?> km/h</p>
                <p class="mb-0">Pressão: <?= $prevision['main']['pressure'] ?> hPa</p>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>

  <script>
    const cidadesPorPaisArray = <?= json_encode($options, JSON_UNESCAPED_UNICODE) ?>;

    const cidadesPorPais = {};
    cidadesPorPaisArray.forEach(c => {
        cidadesPorPais[c.iso3] = c.cities;
    });

    const selectPais = document.getElementById('pais');
    const selectCidade = document.getElementById('cidade');

    selectPais.addEventListener('change', function() {
        const pais = this.value;
        selectCidade.innerHTML = '<option value="">Selecione uma cidade</option>';
        if (cidadesPorPais[pais]) {
            cidadesPorPais[pais].forEach(cidade => {
                const option = document.createElement('option');
                option.value = cidade;
                option.textContent = cidade;
                selectCidade.appendChild(option);
            });
        }
    });
    </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
