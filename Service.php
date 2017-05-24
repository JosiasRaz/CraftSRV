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
            PRIMARY KEY (`id`),
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
        $this->di['db']->exec($sql);
	}

	private function createServerMachine(array $srvMachine_array)
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
}