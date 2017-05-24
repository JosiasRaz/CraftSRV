<?php 
use OAuth\Common\Token\AbstractToken ;
namespace OAuth\OAuth2\Token\Skypulse ;

/**
* 
*/
class SkypulseToken extends \OAuth\Common\Token\AbstractToken
{

	public function __construct($token)
	{
        $this->accessToken = $token ;
        $this->setLifetime(\OAuth\Common\Token\AbstractToken::EOL_NEVER_EXPIRES);
	}
}