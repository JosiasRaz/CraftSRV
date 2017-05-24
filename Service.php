<?php 
/**
* 
*/
namespace Box\Mod\Craftsrv ;

class Service implements \Box\InjectionAwareInterface
{

	protected $di = null ;

	public function setDi($di)
	{
		$this->di = $di ;
	}

	public function getDi()
	{
		return $this->di ;
	}

	public function install()
	{
		$sql = "
        CREATE TABLE IF NOT EXISTS `craftsrv_machine` (
            `id` bigint(20) NOT NULL AUTO_INCREMENT,
            `name` varchar(255) NOT NULL,
            `host` varchar(255) NOT NULL,
            `version` varchar(2) NOT NULL,
            `token` varchar(255) NOT NULL,
            PRIMARY KEY (`id`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
        $this->di['db']->exec($sql);
	}

	public function adminCreateServerMachine(array $srvMachine_array)
	{
        $srvMachine = $this->di['db']->dispense('craftsrv_machine') ;
        foreach ($srvMachine_array as $field => $value) {
        	$srvMachine->$field = $value ;
        }
        $this->di['db']->store($srvMachine);
        return $srvMachine;
	}

	public function uninstall()
    {
        $this->di['db']->exec("DROP TABLE IF EXISTS `craftsrv_machine`");
    }

    public function getSearchQuery($data, $selectStmt = 'SELECT c.*')
    {
        $sql = $selectStmt;
        $sql .= ' FROM `craftsrv_machine` as c';

        $search     = (isset($data['search']) && !empty($data['search'])) ? $data['search'] : NULL;
        $id         = (isset($data['id']) && !empty($data['id'])) ? $data['id'] : NULL;
        $name       = (isset($data['name']) && !empty($data['name'])) ? $data['name'] : NULL;
        $host       = (isset($data['host']) && !empty($data['host'])) ? $data['host'] : NULL;
        $version    = (isset($data['version']) && !empty($data['version'])) ? $data['version'] : NULL;

        $where = array();
        $params = array();
        if($id) {
            $where[] = 'c.id = :id';
            $params[':id'] = $id;
        }

        if($host) {
            $where[] = '(c.host LIKE :host)';
            $host = "%" . $host . "%";
            $params[':host'] = $host;
        }

        if($name) {
            $where[] = '(c.name LIKE :name)';
            $name = "%" . $name . "%";
            $params[':name'] = $name;
        }

        if($version) {
            $where[] = '(c.version LIKE :version)';
            $version = "%" . $version . "%";
            $params[':version'] = $version;
        }

        //smartSearch
        if($search) {
            if(is_numeric($search)) {
                $where[] = 'c.id = :cid';
                $params[':cid'] = $search;
            } else {
                $where[] = "c.name LIKE :s_name OR c.host LIKE :s_host OR c.version LIKE :s_version";
                $search = "%" . $search . "%";
                $params[':s_name'] = $search;
                $params[':s_host'] = $search;
                $params[':s_version'] = $search;
            }
        }

        if (!empty($where)){
            $sql .= ' WHERE '.implode(' AND ', $where);
        }
        $sql = $sql.' ORDER BY c.id asc';

        return array($sql, $params);
    }

    public function toApiArray(\RedBeanPHP\OODBBean $model, $deep = false, $identity = null)
    {
        $details = array(
            'id'    =>  $model->id,
            'name'    =>  $model->name,
            'host'    =>  $model->host,
            'version'    =>  $model->version,
        );

        if ($deep)
            $details['token'] =  $model->token ;

        return $details;
    }

    public function get($data)
    {
        if(!isset($data['id']) && !isset($data['name']))
        {
            throw new \Box_Exception('CraftSRV ID or name is required');
        }

        $db = $this->di['db'] ;
        $craftsrv = null ;

        if(isset($data['id']))
        {
            $craftsrv = $db->findOne('craftsrv_machine', 'id =  ?', array($data['id'])) ;
        }

        if(!$craftsrv && isset($data['name']))
        {
            $craftsrv = $db->findOne('craftsrv_machine', 'name = ?', array($data['name'])) ;
        }

        return $craftsrv ;
    }
}