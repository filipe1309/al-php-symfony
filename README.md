No Diretorio da app:

// Usar img docker do composer para criar projeto
docker run --rm -itv %CD%:/app -w /app composer create-project symfony/skeleton consultorio-alura

// Usar img docker do php para rodar projeto
cd consultorio-alura
docker run --rm -itv %CD%:/app -w /app -p 8080:8080 php -S 0.0.0.0:8080 -t public

composer require annotattion