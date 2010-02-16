<?php
/*
CakePHP jQuery helper.

This helper will make easy to include jQuery source, plugins and scripts.


@copyright: Marco Pegoraro, info(at)consulenza-web.com
@url: http://www.consulenza-web.com/cakephp-jquery-helper.dc-28.html

*/
class JqueryHelper extends Helper {
	
	// Code output settings.
	var $wellCode = 1;
	var $br = "\r\n";
	var $tb = "\t";
	
	var $helpers = Array(
		'html',
		'Javascript',
		'Jcms'
	);
	
	/**
	 * Caricamento del tag "<SCRIPT></SCRIPT>" per l'inclusione della libreria jQuery.
	 * Definisce inoltre una porzione di codice per l'inizializzazione di variabili notevoli
	 * legate all'ambiente CakePHP.
	 */
	function core() {
		
		// Controllo sulla richiesta di una lingua per uniformare l'url Javascript (sul browser)  #
		// alla gestione multilingua ed identificare così in modo corretto la "baseUrl" del       #
		// sito web.                                                                              #
		$url = str_replace('//','/',@$this->params['url']['url']);
		if ( strPos($url,'/lang_') ) {
			$lang = subStr($url,strPos($url,'/lang_')+6,strLen($url));
			$lang = subStr($lang,0,strPos($lang,'/'));
			$url = $lang.'/'.str_replace("/lang_$lang/",'/',$url);
		}
		
		// Calcolo il baseUrl dell'applicazione partendo dalla costante WWW_ROOT fornita da CAKE. #
		$root = WWW_ROOT;
		$root = strRev( subStr( $root, 0, strPos( $root, DS.'app'.DS.'webroot'.DS ) ) );
		$root = strRev( subStr( $root, 0, strPos( $root, DS ) + 1 ) );
		$root = str_replace(DS,'/',$root);
		
		$code = $this->script('jQuery/jQuery').$this->br;
		$code.= '<script type="text/javascript"> /* <![CDATA[ */'.$this->br;
		$code.= 'var __appPath__ = document.location.pathname;'.$this->br;
		$code.= 'var __appBase__ = "'.$root.'";'.$this->br;
		$code.= 'if ( __appPath__.substring(0,__appBase__.length) != __appBase__ ) __appBase__ = "";'.$this->br.$this->tb;
		$code.= 'var __appPlugin__ = "'.@$this->params['plugin'].'";'.$this->br;
		$code.= 'var __appController__ = "'.@$this->params['controller'].'";'.$this->br;
		$code.= 'var __appAction__ = "'.@$this->params['action'].'";'.$this->br;
		$code.= 'var __appLang__ = "'.$this->Jcms->lang().'";'.$this->br;
		//$code.= 'alert("'.$root.'\n"+__appPath__ + "\n"+ __appBase__);';
		$code.= '/* ]]> */</script>'.$this->br;
		
		if ( $this->wellCode ) {
			
			$lines = split( $this->br, $code );
			$code = '';
			foreach ( $lines as $line ) {
				$code.= $this->tb.$line.$this->br;
			}
			$code = trim($code);
			
			$inc1 = $this->tb.'<!-- --------------------------------------------------------------- -->'.$this->br;
			$inc2 = $this->tb.'<!-- jQuery\'s core library with some CakePHP application\'s settings. -->'.$this->br;
			$inc3 = $this->tb.'<!-- --------------------------------------------------------------- -->'.$this->br.$this->br;
			$inc4 = $this->br.$this->tb.'<!-- ############################################################### -->'.$this->br;
			$code = $this->br.$this->br.$inc1.$inc2.$inc3.$this->tb.$code.$this->br.$inc4;
		}
		
		return $code;
	} // EndOf "core()" ###########################################################################
	
	
	# Provide xHTML code to include a jQuery's plugin with it's own css.                          #
	# A plugin must be located in "webroot/js/jQuery/" in a folder named as "pl_pluginname".      #
	# This folder will contain the plugin's core library: "plugin.jquery.js", one or more css     #
	# files named like media: "screen.css" and other files like images, plugin description.       #
	function plugin($name = '', $media = 'screen') {
		// Identify Cake's installation path for calculating relative urls of external script.    #
		$path 	= '/'.strRev(subStr( strRev(ROOT), 0, strPos( str_replace("\\", "/", strRev(ROOT)), "/")));
		
		// Require xHTML code to include the plugin core library.                                 #
		$code = str_replace('"></script>', '"></script>'.$this->br, $this->script('jQuery/pl_'.$name.'/'.$name.'.jquery'));
		
		// This method allow to request multiple css media by passing a comma separated string:   #
		// $jquery->plugin($name, 'screen,print,mobile')                                          #
		foreach ( split(',', $media) as $el ) $code.= $this->pluginCss('jQuery/pl_'.$name.'/', trim($el));
		
		// Forece well formatted code for the page.                                               #
		if ( $this->wellCode ) {
			$pre = $this->br.$this->tb.'<!-- // '.$name.' jQuery\'s Plugin with '.$media.' related CSS -->'.$this->br;
			$code = str_replace( '<script', $this->tb.'<script', $code );
			$code = str_replace( '<link', $this->tb.'<link', $code );
			$code = $pre.$code;
		}
		
		return $code;
	} // EndOf "plugin()" #########################################################################
	
	
	# Provide xHTML code to include multiple jQuery's plugin at once by passing comma separated   #
	# string. This method accept multiple css media like "plugin".                                #
	# $jquery->plugins('interface,iwin,form', 'screen,print');                                    #
	function plugins($names = '', $media = 'screen') {
		$code = '';
		foreach ( split(',', $names) as $el ) $code.= $this->plugin(trim($el), $media);
		
		if ( $this->wellCode ) {
			
			$inc1 = $this->tb.'<!-- --------------------------------------------------------------- -->'.$this->br;
			$inc2 = $this->tb.'<!-- jQuery\'s Required Plugins                                       -->'.$this->br;
			$inc3 = $this->tb.'<!-- --------------------------------------------------------------- -->'.$this->br.$this->br;
			$inc4 = $this->tb.'<!-- ############################################################### -->'.$this->br;
			$code = $this->br.$this->br.$this->br.$this->br.$inc1.$inc2.$inc3.$this->tb.$code.$this->br.$inc4;
			
		}
		
		return $code;
	} // EndOf "plugins()" ########################################################################
	
	
	# Provide code to run a "functional" jQuery file by name.                                     #
	# A call to "blog" script will look for "webroot/js/jq_blog.js".                              #
	# This method allow for loading multiple file once:                                           #
	# $jquery->run("blog,chat,images");                                                           #
	# This method allow for loading annidiated script:                                            #
	# $jquery->run("test/foo,images,blog,communications/chat/write");                             #
	function run($script = '') {
		$code = '';
		foreach( split(',', $script) as $el ) {
			$p = strRev(subStr(strRev(str_replace('\\', '/', trim($el))), strPos(strRev(str_replace('\\', '/', trim($el))), '/'), strLen(strRev(str_replace('\\', '/', trim($el))))));
			if ( strPos($p, '/') ) {
				$s = subStr(trim($el), strLen($p), strLen(trim($el)));
			} else {
				$s = $p;
				$p = '';
			}
			
			// Here a workaround with str_replace: if library's name contains dots                #
			// (like: admin.main) "script" function return a url value without ".js" extension    #
			// so the link doesn't work.                                                          #
			$code.= str_replace('.js.js"></script>', '.js"></script>', str_replace('"></script>', '.js"></script>', $this->script($p.'jq_'.$s)));
		}
		
		if ( $this->wellCode ) {
			
			$code = str_replace( '</script>', '</script>'.$this->br, $code );
			
			$lines = split( $this->br, $code );
			$code = '';
			foreach ( $lines as $line )
				$code.= $this->tb.$line.$this->br;
			$code = trim($code);
			
			$inc1 = $this->tb.'<!-- --------------------------------------------------------------- -->'.$this->br;
			$inc2 = $this->tb.'<!-- jQuery\'s Engines Scripts                                        -->'.$this->br;
			$inc3 = $this->tb.'<!-- --------------------------------------------------------------- -->'.$this->br.$this->br;
			$inc4 = $this->tb.'<!-- ############################################################### -->'.$this->br;
			$code = $this->br.$this->br.$this->br.$this->br.$inc1.$inc2.$inc3.$this->tb.$code.$this->br.$this->br.$inc4;
			
		}
		
		return $code;
	} // Fine "run()" #############################################################################
	
	
	# Provide code to run a controller related script named like:                                 #
	# "webroot/js/jq_controller_controllername.js".                                               #
	function controller() {
		return $this->run('controller_'.@$this->params['controller']);
	} // EndOf: "controller()" ####################################################################
	
	
	# Provide code to run an controller's action related script named like:                       #
	# "webroot/js_controller_controllername_action.js".                                           #
	function controllerAction() {
		return $this->run('controller_'.@$this->params['controller'].'_'.@$this->params['action']);
	} // EndOf: "controllerAction()" ##############################################################
	
	
	# Provide code to run an action related script named like:                                    #
	# "webroot/js_action_actionname.js".                                                          #
	function action() {
		return $this->run('action_'.$this->params['action']);
	} // EndOf: "action()" ########################################################################
	
	
	// Provide a Javascript inclusion tag for jQuery file.                                        #
	function script($url = '', $noExt = false) {
		// Inclusion code will generate only if requested file exists.                            #
		if ( is_File( WWW_ROOT.'js'.DS.str_replace('/', DS, $url).'.js' ) || $noExt )
			return $this->Javascript->link($url);
	} // EndOf "script()" #########################################################################
	
	
	// Provide a CSS inclusion tag for jQuery plugin's stylesheet.                                #
	function pluginCss($plugin = '', $media = '') {
		$file 	= WWW_ROOT.'js'.DS.str_replace('/', DS, $plugin.$media.'.css');
		$css 	= str_replace('"></script>', '', str_replace('<script type="text/javascript" src="', '', str_replace('.js', '.css', $this->script($plugin.$media,true))));
		
		// Inclusion code will generate only if requested file exists.                            #
		if ( is_File( $file ) ) {
			return $this->output('<link rel="stylesheet" type="text/css" href="'.$css.'" media="'.$media.'" />').$this->br;
		}
	} // EndOf "pluginCss()" ######################################################################
	
	
	/**
	 * Write inclusion code for Ext framework working with jQuery.
	 * "core()" method must be called before!
	 */
	function Ext() {
		
		$code = $this->script('Ext/ext-jquery-adapter');
		$code.= $this->script('Ext/ext-all');
		
		// Ext Path definitions.
		$code.= '<script type="text/javascript"> /* <![CDATA[ */'.$this->br;
		$code.= 'Ext.BLANK_IMAGE_URL = __appBase__+"/js/Ext/resources/images/aero/s.gif";'.$this->br;
		$code.= '/* ]]> */</script>'.$this->br;
		
		
		if ( $this->wellCode ) {
			$code = str_replace( '</script>', '</script>'.$this->br, $code );
			
			$lines = split( $this->br, $code );
			$code = '';
			foreach ( $lines as $line )
				$code.= $this->tb.$line.$this->br;
			$code = trim($code);
			
			$inc1 = $this->tb.'<!-- --------------------------------------------------------------- -->'.$this->br;
			$inc2 = $this->tb.'<!-- jQuery\'s Ext Framework Inclusion.                               -->'.$this->br;
			$inc3 = $this->tb.'<!-- --------------------------------------------------------------- -->'.$this->br.$this->br;
			$inc4 = $this->tb.'<!-- ############################################################### -->'.$this->br;
			$code = $this->br.$this->br.$this->br.$this->br.$inc1.$inc2.$inc3.$this->tb.$code.$this->br.$this->br.$inc4;	
		}
		
		
		return $code;
		
	} // EndOf: "Ext()" #################################################################
}
?>