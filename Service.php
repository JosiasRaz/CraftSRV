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
        $craftsrv_host  = (isset($data['craftsrv_host']) && !empty($data['craftsrv_host'])) ? $data['craftsrv_host'] : NULL;
        $craftsrv_version  = (isset($data['craftsrv_version']) && !empty($data['craftsrv_version'])) ? $data['craftsrv_version'] : NULL;

        $where = array();
        $params = array();
        if($id) {
            $where[] = 'c.id = :craftsrv_id';
            $params[':craftsrv_id'] = $id;
        }

        if($craftsrv_host) {
            $where[] = '(c.first_craftsrv_host LIKE :first_craftsrv_host)';
            $craftsrv_host = "%" . $craftsrv_host . "%";
            $params[':first_craftsrv_host'] = $craftsrv_host;
        }

        if($craftsrv_version) {
            $where[] = '(c.first_craftsrv_version LIKE :first_craftsrv_version)';
            $craftsrv_version = "%" . $craftsrv_version . "%";
            $params[':first_craftsrv_version'] = $craftsrv_version;
        }

        //smartSearch
        if($search) {
            if(is_numeric($search)) {
                $where[] = 'c.id = :cid';
                $params[':cid'] = $search;
            } else {
                $where[] = "c.host LIKE :s_host OR c.version LIKE :s_version";
                $search = "%" . $search . "%";
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
            'host'    =>  $model->host,
            'version'    =>  $model->version,
        );

        return $details;
    }
}