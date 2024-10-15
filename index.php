<?php
if (isset($_GET['compile'])) {
  require_once 'controller.php';
  $controller = new Controller();
  $controller->setAsset('css', 'https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css');
  $controller->setAsset('js', 'https://cdn.jsdelivr.net/npm/@tabler/icons@latest/icons-react/dist/index.umd.min.js');
  $controller->setAsset('js', 'https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css');
  $controller->compile();

  echo '<meta http-equiv="refresh" content="0; URL=index.php">';
}

if (isset($_GET['lang'])) {
  $lang = $_GET['lang'];
  try {
    $fileContent = file_get_contents('lang/' . $lang . '.json');
  } catch (Exception $e) {
    $fileContent = file_get_contents('lang/en.json');
  }
  header('Content-Type: application/json');
  echo $fileContent;
  exit;
} ?>
<!DOCTYPE html>
<html id="html-lang" lang="en-US">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>joaopedro.site</title>
  <link rel="stylesheet" href="assets.min.css">
  <script src="assets.min.js"></script>
</head>

<body class="bg-gray-100 font-sans">
  <div id="app" class="container mx-auto px-4 py-8"></div>
</body>

<script>
  async function fetchResumeData(lang) {
    try {
      const response = await fetch('/?lang=' + lang);
      const resumeData = await response.json();

      renderResume(resumeData);
    } catch (error) {
      console.error('Erro ao carregar os dados do currículo:', error);
    }
  }

  function renderResume(data) {
    const app = document.getElementById('app');

    app.innerHTML = `
    <div class="bg-white rounded-lg shadow-lg p-2">
        <div class="flex justify-start m-4 space-x-2">
          <img width="24" height="24" onclick="fetchResumeData('pt-br')" class="cursor-pointer" src="https://flagcdn.com/w320/br.png" class="w-32 h-32">
          <img width="24" height="24" onclick="fetchResumeData('en')" class="cursor-pointer" src="https://flagcdn.com/w320/us.png" class="w-32 h-32">
          <img width="24" height="24" onclick="fetchResumeData('es')" class="cursor-pointer" src="https://flagcdn.com/w320/es.png" class="w-32 h-32">
        </div>
        <hr class="my-2">
        <div class="flex items-center mb-6">
          <img src="${data.profilePicture}" alt="Foto de Perfil" class="w-16 h-16 rounded-full mr-4">
          <div class="flex-1">
            <h1 class="text-2xl font-bold">${data.name}</h1>
            <p class="text-gray-700">${data.position}</p>
            <p class="text-gray-700">Phone: +55 48 991160433</p>
            <p class="text-gray-700">Email: <a href="mailto:joaojkuligowski@proton.me" class="text-blue-500">joaojkuligowski@proton.me</a></p>
          </div>
          <div class="flex-2">
            <button id="btn-print" onclick="preparePrint()" class="cursor-pointer bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Imprimir</button>
          </div>
        </div>
        

        ${data.menu?.length > 10 ? `
        <nav class="w-full mb-8" id="navigation">
          <ul class="flex justify-center space-x-8 text-gray-700 font-semibold text-lg">
            ${data.menu?.map(item => `<li route-link="${item.route}" class="hover:text-blue-500 transition-colors duration-300"><a href="${item.route}">${item.text}</a></li>`).join('')}
          </ul>
        </nav>
        ` : ''}

        <div>
          <p class="text-gray-700">${data.summary}</p>
        </div>

        <hr class="my-6">

        <h3 class="text-xl font-semibold mb-2">${data.sections.experience}</h3>
        <ul>
          ${data.experience.map(job => `
            <li class="mb-4">
              <h4 class="font-medium">${job.title}</h4>
              <p class="text-gray-600">${job.company} | ${job.location} | ${job.period}</p>
              <ul class="list-disc ml-6 text-gray-700">
                ${job.responsibilities.map(task => `<li>${task}</li>`).join('')}
              </ul>
            </li>`).join('')}
        </ul>

        <hr class="my-6">
        
        <h3 class="text-xl font-semibold mb-2">${data.sections.languages}</h3>
        <div class="grid grid-cols-2 gap-4">
          ${data.languages.map(language => `
            <div>
              <h4 class="font-medium">${language.language}</h4>
              <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                <div class="bg-blue-600 h-2.5 rounded-full" style="width: ${language.proficiency}%"></div>
              </div>
              <p class="text-gray-600 text-sm mt-1">${language.fluency}</p>
            </div>`).join('')}
        </div>

        <hr class="my-6">

        <h3 class="text-xl font-semibold mb-2">${data.sections.technologies}</h3>
        <div class="grid grid-cols-2 gap-4">
          <div>
            <h4 class="font-medium mb-1">${data.sections.programmingLanguages}</h4>
            <p class="text-gray-700">${data.skills.programmingLanguages.join(', ')}</p>
          </div>
          <div>
            <h4 class="font-medium mb-1">${data.sections.frameworksAndLibraries}</h4>
            <p class="text-gray-700">${data.skills.frameworksAndLibraries.join(', ')}</p>
          </div>
          <div>
            <h4 class="font-medium mb-1">${data.sections.databases}</h4>
            <p class="text-gray-700">${data.skills.databases.join(', ')}</p>
          </div>
          <div>
            <h4 class="font-medium mb-1">${data.sections.toolsAndTechnologies}</h4>
            <p class="text-gray-700">${data.skills.toolsAndTechnologies.join(', ')}</p>
          </div>
          <div>
            <h4 class="font-medium mb-1">${data.sections.otherSkills}</h4>
            <p class="text-gray-700">${data.skills.otherSkills.join(', ')}</p>
          </div>
        </div>

        <hr class="my-6">

        <h3 class="text-xl font-semibold mb-2">${data.sections.education}</h3>
        <p class="text-gray-700">${data.education.degree} | ${data.education.institution} | ${data.education.period}</p>

        <hr class="my-6">

        <div class="flex justify-center mt-8">
          <a href="https://github.com/joaojkuligowski/joaopedro.site" target="_blank" class="text-blue-500 hover:underline">GitHub</a>
        </div>

      </div>
    `;
  }

  function preparePrint() {
    window.print();
  }

  document.addEventListener('DOMContentLoaded', () => {
    const lang = 'pt-br';
    const app = document.getElementById('app');
    const navigation = document.getElementById('navigation');
    const route = window.location.pathname;
    fetchResumeData(lang);
  });
</script>

</html>