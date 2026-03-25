<?php

namespace App\Models;

use CodeIgniter\Model;

class Permisos_Model extends Base_Empresa_Model
{
    protected $table = 'permisos';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id_usuario', 'id_ruta', 'puede_ver', 'puede_acceder'];

    // Por si usas timestamps en la tabla
    protected $useTimestamps = false;

    /**
     * Obtiene todos los permisos de un usuario
     */
    public function getPermisosPorUsuario($idUsuario)
    {
        return $this->where('id_usuario', $idUsuario)->findAll();
    }

    /**
     * Verifica si un usuario puede ver una ruta
     */
    public function puedeVer($idUsuario, $idRuta)
    {
        return $this->where([
            'id_usuario' => $idUsuario,
            'id_ruta' => $idRuta,
            'puede_ver' => 1
        ])->countAllResults() > 0;
    }

    /**
     * Verifica si un usuario puede acceder manualmente a una ruta (URL)
     */
    public function puedeAcceder($idUsuario, $ruta)
    {
        return $this->select('p.id_permiso')
                    ->from('permisos p')
                    ->join('rutas r', 'r.id = p.id_ruta')
                    ->where('p.id_usuario', $idUsuario)
                    ->where('r.ruta', $ruta)
                    ->where('p.puede_acceder', 1)
                    ->countAllResults() > 0;
    }

    /**
     * Asigna todos los permisos (ver y acceder) a un usuario para todas las rutas
     */
    public function asignarPermisosTotales($idUsuario)
    {
        $db = \Config\Database::connect();
        $sql = "
            INSERT INTO permisos (id_usuario, id_ruta, puede_ver, puede_acceder)
            SELECT ?, r.id, 1, 1
            FROM rutas r
            WHERE r.id NOT IN (
                SELECT id_ruta FROM permisos WHERE id_usuario = ?
            )
        ";
        return $db->query($sql, [$idUsuario, $idUsuario]);
    }

    /**
     * Elimina todos los permisos de un usuario
     */
    public function eliminarPermisosUsuario($idUsuario)
    {
        return $this->where('id_usuario', $idUsuario)->delete();
    }

    public function obtenerRutasConEstadoPorUsuario($idUsuario)
    {

        $sql = "
            SELECT 
                r.id AS id_ruta,
                r.nombre,
                r.ruta,
                r.id_padre,
                r.icono,
                r.orden,
                COALESCE(p.puede_ver, 0) AS puede_ver,
                COALESCE(p.puede_acceder, 0) AS puede_acceder,
                CASE WHEN p.id IS NOT NULL THEN 1 ELSE 0 END AS tiene_permiso
            FROM rutas r
            LEFT JOIN permisos p 
                ON p.id_ruta = r.id 
                AND p.id_usuario = ?
            ORDER BY 
                COALESCE(r.id_padre, r.id), 
                r.id_padre IS NOT NULL, 
                r.orden ASC
        ";

        $query = $this->query($sql, [$idUsuario]);

        return $query->getResultArray();

    }

    public function sincronizarPermisos(array $permisos, $id_usuario)
    {
        log_message('debug', '🔹 Iniciando sincronización de permisos para usuario: ' . $id_usuario);
        log_message('debug', '🔹 Permisos recibidos: ' . print_r($permisos, true));

        foreach ($permisos as $p) {
            $ruta_id = $p['ruta_id'];           
            $activo  = (int)$p['activo'];

            log_message('debug', "➡️ Procesando ruta_id={$ruta_id}, activo={$activo}");

            if ($activo === 1) {            
                $existe = $this->where([
                    'id_ruta'    => $ruta_id,
                    'id_usuario' => $id_usuario                   
                ])->first();

                if (!$existe) {
                    $this->insert([
                        'id_usuario'    => $id_usuario,
                        'id_ruta'       => $ruta_id,
                        'puede_ver'     => 1,
                        'puede_acceder' => 1
                    ]);
                    log_message('debug', "✅ Insertado permiso para ruta {$ruta_id}");
                } else {
                    log_message('debug', "🟡 Ya existía permiso para ruta {$ruta_id}, no se inserta");
                }
            } else {
                $deleted = $this->where([
                    'id_ruta'    => $ruta_id,
                    'id_usuario' => $id_usuario
                ])->delete();

                if ($deleted) {
                    log_message('debug', "❌ Eliminado permiso de ruta {$ruta_id}");
                } else {
                    log_message('debug', "ℹ️ No existía permiso para ruta {$ruta_id}, nada que eliminar");
                }
            }
        }

        log_message('debug', '✅ Sincronización completada para usuario: ' . $id_usuario);
    }


}
