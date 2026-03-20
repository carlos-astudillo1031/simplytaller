<?php

namespace App\Models;

use CodeIgniter\Model;

class Permisos_Especiales_Usuario_Model extends Base_Empresa_Model
{
    protected $table            = 'permisos_especiales_usuario';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';

    protected $allowedFields    = [
        'permiso_id',
        'id_usuario'
    ];

    protected $useTimestamps    = false;

    // Opcional: obtener permisos por usuario
    public function getPermisosByUsuario($idUsuario)
    {
        return $this->select('permisos_especiales.*')
            ->join('permisos_especiales', 'permisos_especiales.id = permisos_especiales_usuario.permiso_id')
            ->where('permisos_especiales_usuario.id_usuario', $idUsuario)
            ->where('permisos_especiales.activo', 1)
            ->findAll();
    }

    public function sincronizarPermisosEspeciales(array $permisos, $id_usuario)
    {
        log_message('debug', '🔹 Iniciando sincronización de permisos especiales para usuario: ' . $id_usuario);
        log_message('debug', '🔹 Permisos especiales recibidos: ' . print_r($permisos, true));

        foreach ($permisos as $p) {
            $permiso_especial_id = $p['permiso_especial_id'];           
            $activo  = (int)$p['activo'];

            log_message('debug', "➡️ Procesando permiso_especial_id={$permiso_especial_id}, activo={$activo}");

            if ($activo === 1) {            
                $existe = $this->where([
                    'permiso_id'    => $permiso_especial_id,
                    'id_usuario' => $id_usuario                   
                ])->first();

                if (!$existe) {
                    $this->insert([
                        'id_usuario'    => $id_usuario,
                        'permiso_id'       => $permiso_especial_id
                    ]);
                    log_message('debug', "✅ Insertado permiso para permiso_especial_id {$permiso_especial_id}");
                } else {
                    log_message('debug', "🟡 Ya existía permiso para permiso_especial_id {$permiso_especial_id}, no se inserta");
                }
            } else {
                $deleted = $this->where([
                    'permiso_id'    => $permiso_especial_id,
                    'id_usuario' => $id_usuario
                ])->delete();

                if ($deleted) {
                    log_message('debug', "❌ Eliminado permiso de permiso_especial_id {$permiso_especial_id}");
                } else {
                    log_message('debug', "ℹ️ No existía permiso para permiso_especial_id {$permiso_especial_id}, nada que eliminar");
                }
            }
        }

        log_message('debug', '✅ Sincronización permisos especiales completada para usuario: ' . $id_usuario);
    }
}
