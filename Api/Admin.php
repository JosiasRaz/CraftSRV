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
		$this->check($data) ;
        $service = $this->getService();
        return $service->adminCreateServerMachine($data);
	}

    public function check($data)
    {
        $required = array(
            'host'      => 'Host required',
            'version'   => 'Version required',
            'token'     => 'Token required',
        ) ;
        if (is_numeric($data['version']))
            $data['version'] = 'v'.$data['version'] ;
        if (!preg_match('`v[0-9]+`', $data['version']))
            throw new \Box_Exception('Version is not valid', 701) ;
        $validator = $this->di['validator'];
        $validator->checkRequiredParamsForArray($required, $data);
    }

    public function update($data)
    {
        $this->check($data) ;
        $service = $this->getService() ;
        return $service->adminUpdateServerMachine($data) ;
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
        $deep = (isset($data['deep'])) ? $data['deep'] : false ;
        $craftsrv = $service->toApiArray($craftsrv, $deep, $this->getIdentity()) ;

        if ($deep)
        {
            $craftsrv_setting = $service->get_setting($craftsrv) ;
            $craftsrv['ip'] = $craftsrv_setting->serverDefaultNetworkAddress ;
            $craftsrv['logo_url'] = $craftsrv_setting->panelLogo  ;
            $craftsrv['occupied_ports'] = $service->get_restricted_ports($craftsrv) ;
        }

        return $craftsrv ;
    }

    public function get_games()
    {
        $craftsrvs = $this->get_list(array())['list'] ;
        $all_games = array() ;
        foreach($craftsrvs as $craftsrv)
        {
            $games = $this->getService()->getSupportedGames($craftsrv) ;
            foreach ($games as $game) {
                $all_games[$game->name] = $game->name ;
            }
        }
        return $all_games ;
    }

    public function get_plans()
    {
        $craftsrvs = $this->get_list(array())['list'] ;
        $all_plans = array() ;
        foreach($craftsrvs as $craftsrv)
        {
            $plans = $this->getService()->getPlans($craftsrv) ;
            foreach ($plans as $plan) {
                $all_plans[$plan->id] = $plan->name ;
            }
        }
        return array_unique($all_plans) ;
    }

    public function get_servers()
    {
        $craftsrvs = $this->get_list(array())['list'] ;
        $all_servers = array() ;
        foreach($craftsrvs as $craftsrv)
        {
            $servers = $this->getService()->getServers($craftsrv) ;
            foreach ($servers as $server) {
                $all_servers[] = $server->name ;
            }
        }
        return array_unique($all_servers) ;
    }

    public function createUser($user)
    {
        $craftsrv_id = $user['server_id'] ;
        $craftsrvs = $this->get_list(array('search'=>$craftsrv_id))['list'] ;
        $craftsrv = array_shift($craftsrvs) ;
        unset($user['server_id']) ;
        $user['locked'] = false ;
        $user['powerUser'] = false ;
        return $this->getService()->createUser($craftsrv, $user) ;
    }

    public function createServer($server)
    {
        $craftsrv = $server['craftsrv'] ;
        unset($server['craftsrv']) ;
        return $this->getService()->createServer($craftsrv, $server) ;
    }

    public function getUnusedPort($craftsrv)
    {
        $port_ranges = explode('-', $craftsrv['port_ranges']) ; 
        $ports_used = explode(', ', $craftsrv['occupied_ports']) ;
        $port_unused = 1 ;
        for ($i=$port_ranges[0]; $i <= $port_ranges[1]; $i++) { 
            if (!in_array($i, $ports_used))
            {
                $port_unused = $i ;
                break ;
            }
        }
        return $port_unused ;
    }

    public function delete($data)
    {
        $required = array(
            'id' => 'CraftSRV id is missing',
        ) ;
        $this->di['validator']->checkRequiredParamsForArray($required, $data);
        $model = $this->di['db']->getExistingModelById('craftsrv_machine', $data['id'], 'CraftSRV not found') ;
        $this->di['events_manager']->fire(array('event'=>'onBeforeAdminCraftSRVDelete', 'params'=>array('id'=>$model->id)));
        $id = $model->id;
        $this->getService()->remove($model);
        $this->di['events_manager']->fire(array('event'=>'onAfterAdminCraftSRVDelete', 'params'=>array('id'=>$id)));
        return true ;
    }

    public function craftsrv_test_connection($data)
    {
        $required = array(
            'id' => 'Craftsrv id is missing',
        ) ;
        $this->di['validator']->checkRequiredParamsForArray($required, $data);
        $model = $this->di['db']->getExistingModelById('craftsrv_machine', $data['id'], 'CraftSRV not found');
        $service = $this->getService() ;
        $craftsrv = $service->toApiArray($model,true,$this->getIdentity()) ;
        return (bool) $this->getService()->testConnection($craftsrv) ;
    }
}