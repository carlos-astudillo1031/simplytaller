<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Models\Empresa_Model;
use DateTime;

class BackupDatabases extends BaseCommand
{
    protected $group       = 'Custom';
    protected $name        = 'backup:run';
    protected $description = 'Respalda la base de datos default.';

    public function run(array $params)
    {
        //Eliminando respaldos anteriores a 3 dias
        $backupRoot = WRITEPATH . 'backups/';
        $retencionDias = 3;

        if (is_dir($backupRoot)) {
            
            $limite = strtotime(date('Y-m-d', strtotime("-{$retencionDias} days")));

            foreach (scandir($backupRoot) as $carpeta) {

                if ($carpeta === '.' || $carpeta === '..') {
                    continue;
                }

                $rutaCarpeta = $backupRoot . $carpeta;

                if (!is_dir($rutaCarpeta)) {
                    continue;
                }

                // Solo considerar carpetas con formato YYYY-MM-DD
                $fecha = DateTime::createFromFormat('Y-m-d', $carpeta);

                if (!$fecha) {
                    continue;
                }

                if ($fecha->getTimestamp() < $limite) {

                    CLI::write("Eliminando respaldo antiguo: {$carpeta}", 'yellow');

                    $this->eliminarDirectorio($rutaCarpeta);
                }
            }
        }

        //Iniciando respaldo de BD
        $user = env('database.default.username');
        $pass = env('database.default.password');

        $empresaModel = new Empresa_Model();
        $empresas = $empresaModel->getEmpresasActivas();

        $fecha = date('Y-m-d');
        $ruta = WRITEPATH . "backups/{$fecha}/";

        // Crear carpeta si no existe
        if (!is_dir($ruta)) {
            if (!mkdir($ruta, 0775, true) && !is_dir($ruta)) {
                CLI::error("No se pudo crear la carpeta de backups: {$ruta}");
                return;
            }
        }

        foreach ($empresas as $empresa) {

            $nombreEmpresa = strtolower($empresa['nombre']);

            $nombreEmpresa = preg_replace(
                '/[^a-z0-9]+/',
                '_',
                iconv('UTF-8', 'ASCII//TRANSLIT', $nombreEmpresa)
            );

            $nombreEmpresa = trim($nombreEmpresa, '_');

            $dbName = $empresa['db_nombre'];

            $archivo = $ruta . "{$nombreEmpresa}_{$dbName}.sql.gz";

            $comando = "mysqldump -u {$user} -p'{$pass}' {$dbName} | gzip > {$archivo}";

            CLI::write("Respaldando {$empresa['nombre']} ({$dbName})", 'yellow');

            $output = [];
            $result = 0;

            exec($comando . " 2>&1", $output, $result);

            if ($result === 0 && file_exists($archivo) && filesize($archivo) > 0) {

                CLI::write("✔ Backup generado: {$archivo}", 'green');
                CLI::write("📦 Tamaño: " . round(filesize($archivo) / 1024, 2) . " KB", 'green');

            } else {

                CLI::error("✖ Error respaldando {$empresa['nombre']}");

                foreach ($output as $line) {
                    CLI::write($line, 'red');
                }
            }
        }

        CLI::write("Proceso finalizado.", 'green');
    }

    private function eliminarDirectorio(string $directorio): void
    {
        if (!is_dir($directorio)) {
            return;
        }

        $elementos = scandir($directorio);

        foreach ($elementos as $elemento) {

            if ($elemento === '.' || $elemento === '..') {
                continue;
            }

            $ruta = $directorio . DIRECTORY_SEPARATOR . $elemento;

            if (is_dir($ruta)) {
                $this->eliminarDirectorio($ruta);
            } else {
                unlink($ruta);
            }
        }

        rmdir($directorio);
    }

}