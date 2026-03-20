<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * Modelo para la tabla `bomba`
 * Basado en la estructura de vehículo, pero:
 *   • Reemplaza `patente` por `codigo`
 *   • Elimina `chasis`, `anio` y `color`
 */
class Bomba_Model extends Base_Empresa_Model
{
    /** @var string Nombre de la tabla */
    protected $table      = 'bomba';

    /** @var string Clave primaria */
    protected $primaryKey = 'id';

    /** @var array Campos admitidos para inserción / actualización */
    protected $allowedFields = [
        'codigo',        // código interno o de serie de la bomba
        'id_marca',      // relación con la marca del vehículo/equipo
        'id_modelo',     // relación con el modelo
        'id_cliente',    // propietario      
        'token',         // token único (si sigues usando el mismo esquema)
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /** @var bool Manejo automático de timestamps */
    protected $useTimestamps = true;

    /** @var bool Soft deletes habilitados */
    protected $useSoftDeletes = true;

    /** @var string Campo de creación */
    protected $createdField  = 'created_at';

    /** @var string Campo de actualización */
    protected $updatedField  = 'updated_at';

    /** @var string Campo de borrado lógico */
    protected $deletedField  = 'deleted_at';


    public function GetBombasPorCliente($id_cliente){
        $db = $this->db;
        $builder = $db->table('bomba b');

        $builder->select('b.*, m.nombre as marca, mo.nombre as modelo, b.token');
        $builder->join('marca m', 'm.id = b.id_marca');
        $builder->join('modelo mo', 'mo.id = b.id_modelo');       
        $builder->where('b.id_cliente', $id_cliente);
        $builder->where('b.deleted_at', null);

        $query = $builder->get();
        $bombas = $query->getResult();
        return $bombas;
    }

    public function getBombaConClientePorToken(string $token)
    {
        return $this->select('bomba.*, cliente.nombre_cliente AS cliente_nombre, cliente.email_cliente AS cliente_email, cliente.telefono_cliente AS cliente_telefono, marca.nombre as marca, modelo.nombre as modelo')
                    ->join('cliente', 'cliente.id_cliente = bomba.id_cliente')
                    ->join('marca', 'marca.id = bomba.id_marca')
                    ->join('modelo', 'modelo.id = bomba.id_modelo')
                    ->where('bomba.token', $token)
                    ->first();
    }

    public function getBombaConClientePorId(int $id)
    {
        return $this->select('bomba.*, cliente.nombre_cliente AS cliente_nombre, cliente.email_cliente AS cliente_email, cliente.telefono_cliente AS cliente_telefono, marca.nombre as marca, modelo.nombre as modelo')
                    ->join('cliente', 'cliente.id_cliente = bomba.id_cliente')
                    ->join('marca', 'marca.id = bomba.id_marca')
                    ->join('modelo', 'modelo.id = bomba.id_modelo')
                    ->where('bomba.id', $id)
                    ->first();
    }


}
