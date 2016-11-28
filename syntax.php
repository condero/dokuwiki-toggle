<?php
/**
 * Toggle Plugin: toggles visibility of texts with syntax <TOGGLE></TOGGLE>
 * 
 * @license		GPL3 (http://www.gnu.org/licenses/gpl.html)
 * @author	 	condero Aktiengesellschaft <info@condero.com>
 */

if(!defined('DOKU_INC')) define('DOKU_INC',realpath(dirname(__FILE__).'/../../').'/');
if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');
require_once(DOKU_PLUGIN.'syntax.php');

/**
 * All DokuWiki plugins to extend the parser/rendering mechanism
 * need to inherit from this class
 */
class syntax_plugin_toggle extends DokuWiki_Syntax_Plugin {
	
	/**
	 * return some info
	 */
	function getInfo(){
		return array(
			'author'	=> 'condero Aktiengesellschaft',
			'email'		=> 'info@condero.com',
			'date'		=> '2016-11-28',
			'name'		=> 'Toggle Plugin',
			'desc'		=> 'This plugin toggles visibility of text.',
			'url'		=> 'http://www.dokuwiki.org/plugin:toggle',
		);
	}

	/**
	 * What kind of syntax are we?
	 */
	function getType(){
		return 'protected';
	}

	/**
	 * Where to sort in?
	 */ 
	function getSort(){
		return 202;
	}

	/**
	 * Connect pattern to lexer
	 */
	function connectTo($mode) {
		$this->Lexer->addEntryPattern('<TOGGLE>(?=.*?</TOGGLE>)',$mode,'plugin_toggle');
	}

	function postConnect() {
		$this->Lexer->addExitPattern('</TOGGLE>','plugin_toggle');
	}

	/**
	 * Handle the match
	 */
	function handle($match, $state, $pos, &$handler){
		switch ($state) {
			case DOKU_LEXER_ENTER : 
				return array($state, '');
			break;
			case DOKU_LEXER_UNMATCHED :
				return array($state, $match);
			break;
			case DOKU_LEXER_EXIT :
				return array($state, '');
			break;
		}
		return array();
	}

	/**
	 * Create output
	 */
	function render($mode, &$renderer, $data) {
		if($mode == 'xhtml'){
			list($state, $match) = $data;
			switch ($state) {
				case DOKU_LEXER_ENTER :
					$renderer->doc.= '';
					break;
				case DOKU_LEXER_UNMATCHED :
					$renderer->doc.= '<span class="plugin_toggle" style="padding:2px; border:1px solid; cursor:pointer;" onclick="javascript:this.innerHTML=this.innerHTML==\'********\'?this.dataset.text:\'********\';" data-text="'.$match.'">********</span>';
					break;
				case DOKU_LEXER_EXIT :
					$renderer->doc .= "";
					break;
			}
			return true;
		}
		return false;
	}
}
?>