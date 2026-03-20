<?php

namespace App\Models;

use CodeIgniter\Model;

class Rutas_Model extends Base_Empresa_Model
{
    protected $table            = 'rutas';
    protected $primaryKey       = 'id';
    protected $allowedFields    = [
        'nombre',
        'ruta',
        'controlador',
        'metodo',
        'id_padre',
        'icono',
        'orden',
        'activo',
        'visible_menu'
    ];

    /**
     * Obtiene todas las rutas activas ordenadas
     */
    public function obtenerRutasActivas()
    {
        return $this->where('activo', 1)
                    ->orderBy('orden', 'ASC')
                    ->findAll();
    }

    /**
     * Obtiene todas las rutas principales (sin padre)
     */
    public function obtenerRutasPrincipales()
    {
        return $this->where(['padre_id' => null, 'activo' => 1])
                    ->orderBy('orden', 'ASC')
                    ->findAll();
    }

    /**
     * Obtiene las subrutas de un menú padre
     */
    public function obtenerSubrutas($padreId)
    {
        return $this->where(['padre_id' => $padreId, 'activo' => 1])
                    ->orderBy('orden', 'ASC')
                    ->findAll();
    }

    /**
     * Obtiene un menú jerárquico (padres + hijos)
     */
    public function obtenerMenuJerarquico()
    {
        $menu = [];
        $padres = $this->obtenerRutasPrincipales();

        foreach ($padres as $padre) {
            $padre['subrutas'] = $this->obtenerSubrutas($padre['id']);
            $menu[] = $padre;
        }

        return $menu;
    }

    /**
     * Busca una ruta específica por URL exacta
     */
    public function buscarPorRuta($ruta)
    {
        return $this->where('ruta', $ruta)->first();
    }

    //trae las ruts visibles en el menu
    public function getRutasPorUsuario($id_usuario)
    {
        return $this->select('rutas.id, rutas.nombre, rutas.ruta, rutas.icono, rutas.id_padre')
            ->join('permisos', 'permisos.id_ruta = rutas.id', 'inner')
            ->where('permisos.id_usuario', $id_usuario)
            ->where('permisos.puede_ver', 1)
            ->where('rutas.visible_menu', 1)
            ->orderBy('rutas.orden', 'ASC')
            ->findAll();
    }

    //Esto trae las visibles e invisibles
    public function getRutasPorUsuarioTodas($id_usuario)
    {
        return $this->select('rutas.id, rutas.nombre, rutas.ruta, rutas.icono, rutas.id_padre')
            ->join('permisos', 'permisos.id_ruta = rutas.id', 'inner')
            ->where('permisos.id_usuario', $id_usuario)
            ->where('permisos.puede_ver', 1)            
            ->orderBy('rutas.orden', 'ASC')
            ->findAll();
    }
}
