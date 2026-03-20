<?php 
namespace App\Models;

use CodeIgniter\Model;
use App\Models\Base_Empresa_Model;


class Usuario_Model extends Base_Empresa_Model{

	protected $table      = 'usuario';

    protected $primaryKey = 'id_usuario';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';

    protected $useSoftDeletes = true;

    protected $allowedFields = ['nombre','username','password','id_tipo_usuario'];


    protected $useTimestamps = true;

    protected $createdField  = 'created_at';

    protected $updatedField  = 'updated_at';

    protected $deletedField  = 'deleted_at';


    
    public function getUsuarioPorNombre(string $usuario)
    {
        $builder = $this->db->table('usuario u');

        $builder->select('u.id_usuario, u.nombre, u.id_tipo_usuario, u.password, tu.nombre as cargo')
                ->join('tipo_usuario tu', 'tu.id = u.id_tipo_usuario')
                ->where('u.username', strtolower($usuario))
                ->where('u.deleted_at', null);

        $query = $builder->get();
        return $query->getRow();
    }


}



?>