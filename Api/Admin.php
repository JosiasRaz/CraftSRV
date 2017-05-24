<?php 
/**
* 
*/
namespace Box\Mod\Craftsrv\Api ;

class Admin extends \Api_Abstract
{
    CONST URL_APPEND = '/api' ;

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

	public function get_list($data)
	{
        $per_page = $this->di['array_get']($data, 'per_page', $this->di['pager']->getPer_page());
        list($sql, $params) = $this->getService()->getSearchQuery($data);
        $pager = $this->di['pager']->getSimpleResultSet($sql, $params, $per_page);
        foreach($pager['list'] as $key => $craftsrvArr){
            $craftsrv = $this->di['db']->getExistingModelById('craftsrv_machine', $craftsrvArr['id'], 'CraftSRV Machines not found');
            $pager['list'][$key] = $this->getService()->toApiArray($craftsrv, true, $this->getIdentity());
        }

        return $pager;
	}

    public function get_url_append()
    {
        return self::URL_APPEND ;
    }

    public function get($data)
    {
        $service = $this->getService() ;
        $craftsrv = $service->get($data) ;
        $craftsrv = $service->toApiArray($craftsrv, $data['deep'], $this->getIdentity()) ;

        if (isset($data['deep']) && $data['deep'])
        {
            var_dump($service->get_ip($craftsrv)) ; die() ;
            $craftsrv['ip'] = $service->get_ip($craftsrv) ;
        }
        else
            $data['deep'] = false ;

        return $craftsrv ;
    }
}