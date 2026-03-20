<?php 



namespace App\Models;



use CodeIgniter\Model;



class Cliente_Model extends Base_Empresa_Model{

	protected $table      = 'cliente';
    protected $primaryKey = 'id_cliente';



    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = true;

    protected $allowedFields = ['rut_cliente','nombre_cliente','email_cliente','telefono_cliente','direccion_cliente'];



    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';    




}



?>