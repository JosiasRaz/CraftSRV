<?php 
/**
* 
*/
namespace Box\Mod\Craftsrv\Api ;

class Admin extends \Api_Abstract
{
	public function create($data)
	{
		$required = array(
			'host'  	=> 'Host required',
			'version'  	=> 'Version required',
			'token'  	=> 'Token required',
		) ;
        $validator = $this->di['validator'];
        $validator->checkRequiredParamsForArray($required, $data);
        $service = $this->getService();
        return $service->adminCreateServerMachine($data);
	}
}