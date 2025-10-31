# 🌤️ LocalWeather — Consulta de Clima por Cidade e País

Aplicação simples em **PHP** que consome a **API OpenWeather** para exibir informações meteorológicas de uma cidade informada pelo usuário.  
Desenvolvida com **GuzzleHTTP** e interface minimalista com imagens do **Unsplash**.

---

## 🧩 Estrutura do Projeto

<pre><code>
api/
│
├── components/
│   └── options.php           # Lista de países com código ISO
│
├── process/
│   └── process.php           # Lógica de comunicação com a API OpenWeather
│
└── public/
    ├── index.php             # Página principal com formulário e exibição do clima
    ├── process.php           # Require para o process/process.php
    └── css/
        └── style.css         # Estilização visual do formulário e do resultado
</code></pre>

---

## ⚙️ Tecnologias Utilizadas

- **PHP 8+**
- **Composer**
- **GuzzleHTTP** → Requisições HTTP elegantes e modernas  
- **OpenWeather API** → Dados meteorológicos em tempo real  
- **HTML5 / CSS3**
