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
}