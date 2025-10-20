<?php
declare(strict_types=1);

/**
 * Instalador simples: cria o schema se não existir.
 * Requer: php/includes/config.php com credenciais válidas.
 */

header('Content-Type: text/plain; charset=utf-8');

$root = dirname(__DIR__, 1);
require_once $root . '/includes/config.php';

$dsnNoDb = sprintf('mysql:host=%s;port=%s;charset=utf8mb4', DB_HOST, DB_PORT ?? '3306');

try {
  $pdo = new PDO($dsnNoDb, DB_USER, DB_PASS, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
  ]);

  $sql = file_get_contents(dirname(__DIR__, 2) . '/migrations/001_init.sql');
  if ($sql === false) {
    http_response_code(500);
    exit("Erro: não consegui ler migrations/001_init.sql\n");
  }

  // Permite múltiplas statements
  foreach (array_filter(array_map('trim', preg_split('/;\s*[\r\n]+/m', $sql))) as $stmt) {
    if ($stmt === '') continue;
    $pdo->exec($stmt . ';');
  }

  echo "OK: schema instalado/atualizado.\n";
  echo "Se já havia dados, use phpMyAdmin -> Exportar/Importar para migrá-los.\n";
} catch (Throwable $e) {
  http_response_code(500);
  echo "Falha na instalação: " . $e->getMessage() . "\n";
}