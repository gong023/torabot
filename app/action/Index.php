<?php
/**
 *  Index.php
 *
 *  @author	{$author}
 *  @package   Torabot
 *  @version   $Id$
 */

/**
 *  Index form implementation
 *
 *  @author	{$author}
 *  @access	public
 *  @package   Torabot
 */

class Torabot_Form_Index extends Torabot_ActionForm
{
	/**
	 *  @access   private
	 *  @var	  array   form definition.
	 */
	var $form = array(
		'test' => array(
			'type'		=> VAR_TYPE_TEXT,	// Input type
			'name'		=> 'test',		// Display name
			'required'	=> false,			// Required Option(true/false)
			),
		'oauth_token' => array(
			'type'		=> VAR_TYPE_TEXT,	// Input type
			'name'		=> 'oauth_token',		// Display name
			'required'	=> false,			// Required Option(true/false)
			),
	);
}

/**
 *  Index action implementation.
 *
 *  @author	 {$author}
 *  @access	 public
 *  @package	Torabot
 */
class Torabot_Action_Index extends Torabot_ActionClass
{
	/**
	 *  preprocess Index action.
	 *
	 *  @access	public
	 *  @return	string  Forward name (null if no errors.)
	 */
	function prepare()
	{
		if ($this->af->validate() > 0) {
			$this->af->setApp('error', 'sorry,top page error');
			return 'error';
		}
		session_start();
		if ($this->af->get('test') == 'clear') {
			session_destroy();
			session_start();
		}
		$this->setOAuth();
		$oauth_token = $this->af->get('oauth_token');
		//callbackしたのになぜかこっち来ちゃった時
		if ($oauth_token && $this->oauth_state === 'start') {
			$this->jumpToPage('callback');
		}
		return null;
	}

	/**
	 *  Index action implementation.
	 *
	 *  @access	public
	 *  @return	string  Forward Name.
	 */
	function perform()
	{
		// -- 初回呼び出し時
		$this->oauth->getRequestToken('https://twitter.com/oauth/request_token', CALLBACK);
		$_SESSION['request_token'] = $this->oauth->getToken();
		$_SESSION['request_token_secret'] = $this->oauth->getTokenSecret();
		$this->oauth_state = "start";
		/* authorization URL を取得 */
		$request_link = $this->oauth->getAuthorizeURL('https://twitter.com/oauth/authorize');
		/* authorization URLのリンクを作成 */
		$this->af->setApp('request_link', $request_link);
		return 'index';
	}
}

?>
