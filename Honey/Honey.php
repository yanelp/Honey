
<?php
require_once( config_get( 'class_path' ) . 'MantisPlugin.class.php' );

class HoneyPlugin extends MantisPlugin {

	/**
	 *  A method that populates the plugin information and minimum requirements.
	 */
	function register( ) {
		$this->name = plugin_lang_get( 'title' );
		$this->description = plugin_lang_get( 'description' );
		//$this->page = 'config';

		$this->version = '0.1';
		$this->requires = array(
			'MantisCore' => '1.2.0',
		);

		$this->author = 'Cecy_Jane';
		$this->contact = 'cecy_jane@unlp.edu.ar';
		$this->url = 'http://www.mantisbt.org';
	}

	/**
	 * Install plugin function.
	 */
	function install() {

		return true;

	}

	/*
	 * Default plugin configuration.
	 */
	function hooks( ) {
		$t_hooks = array(
		//	'EVENT_MENU_MAIN'  => 'print_menu_trace',
			'EVENT_MENU_MAIN'  => 'print_menu_lel',
			'EVENT_LAYOUT_RESOURCES' => 'include_js',
		);
		return array_merge( parent::hooks(), $t_hooks );
	}


//los 2 primeros casilleros en blanco se deben a que estuve probando crear 2 tablas y quedaron registradas en el vector del esquema, con lo cual si agrego una nueva tabla o cambio va debajo de lo que ya escribí- porque compara los casilleros llenos del vector con los que tenía llenos la vez que instaló o actualizó el plugin. hay que ver la manera de borrar esos registros para no tener que desinstalar

	function schema() {

		return array(

 //CREACION DE MODELO PARA LEL

	   array( 'CreateTableSQL', array( plugin_table( 'symbol' ), "
				id			I		NOTNULL UNSIGNED AUTOINCREMENT PRIMARY,
				name		C(50)	NOTNULL DEFAULT \" '' \" ,
				notion      C(200)  NOTNULL DEFAULT \" '' \",
				type		I   	NOTNULL ,
				id_project  I NOTNULL  CONSTRAINTS 'FOREIGN KEY REFERENCES bugtracker.mantis_project_table.id'
				",
				array( 'mysql' => 'DEFAULT CHARSET=utf8' ) )
			),
			array( 'CreateTableSQL', array( plugin_table( 'synonymous' ), "
				id			I		NOTNULL UNSIGNED AUTOINCREMENT PRIMARY,
				name		C(50)	NOTNULL DEFAULT \" '' \" ,
				id_symbol   I NOTNULL  CONSTRAINTS 'FOREIGN KEY REFERENCES bugtracker.mantis_project_table.id'
				",
				array( 'mysql' => 'DEFAULT CHARSET=utf8' ) )
			),
			array( 'CreateTableSQL', array( plugin_table( 'impact' ), "
				id		    	I		NOTNULL UNSIGNED AUTOINCREMENT PRIMARY,
				description		C(200)	NOTNULL DEFAULT \" '' \" , 
				id_symbol       I NOTNULL  CONSTRAINTS 'FOREIGN KEY REFERENCES bugtracker.mantis_project_table.id'
				",
				array( 'mysql' => 'DEFAULT CHARSET=utf8' ) )
			),
   
  
	//CREACION DE MODELO PARA CASOS DE USO

	array( 'CreateTableSQL', array( plugin_table( 'derivation' ), "
				id		    	I		NOTNULL UNSIGNED AUTOINCREMENT PRIMARY,
				date		    T NOTNULL DEFAULT '0',
				active 			I1 NOTNULL DEFAULT '0'
				",
				array( 'mysql' => 'DEFAULT CHARSET=utf8' ) )
			),

	  array( 'CreateTableSQL', array( plugin_table( 'usecase' ), "
				id		    	I		NOTNULL UNSIGNED AUTOINCREMENT PRIMARY,
				name		C(50)	NOTNULL DEFAULT \" '' \" , 
				goal		B , 
				postconditions  C(255) ,	
				observations    C(255) ,	
				preconditions   C(255) ,
				id_derivation   I    CONSTRAINTS 'FOREIGN KEY REFERENCES bugtracker.mantis_derivation_table.id',
				id_symbol		I    CONSTRAINTS 'FOREIGN KEY REFERENCES bugtracker.mantis_symbol_table.id',
				id_project       I NOTNULL  CONSTRAINTS 'FOREIGN KEY REFERENCES bugtracker.mantis_project_table.id'
				",
				array( 'mysql' => 'DEFAULT CHARSET=utf8' ) )
			),
				

        array( 'CreateTableSQL', array( plugin_table( 'usecase_include' ), "
				id		    	I		NOTNULL UNSIGNED AUTOINCREMENT PRIMARY,
				id_usecase_parent I NOTNULL  CONSTRAINTS 'FOREIGN KEY REFERENCES bugtracker.mantis_plugin_honey_usecase_table.id' ,
				id_usecase_include I NOTNULL  CONSTRAINTS 'FOREIGN KEY REFERENCES bugtracker.mantis_plugin_honey_usecase_table.id'
				",
				array( 'mysql' => 'DEFAULT CHARSET=utf8' ) )
			),

		array( 'CreateTableSQL', array( plugin_table( 'usecase_extend' ), "
				id		    	I		NOTNULL UNSIGNED AUTOINCREMENT PRIMARY,
				id_usecase_parent I NOTNULL  CONSTRAINTS 'FOREIGN KEY REFERENCES bugtracker.mantis_plugin_honey_usecase_table.id' ,
				id_usecase_extends I NOTNULL  CONSTRAINTS 'FOREIGN KEY REFERENCES bugtracker.mantis_plugin_honey_usecase_table.id'
				",
				array( 'mysql' => 'DEFAULT CHARSET=utf8' ) )
			),


		array( 'CreateTableSQL', array( plugin_table( 'actor' ), "
				id		    	I		NOTNULL UNSIGNED AUTOINCREMENT PRIMARY,
				name		    C(50)	NOTNULL DEFAULT \" '' \" ,
				description		B,
				id_derivation   I    CONSTRAINTS 'FOREIGN KEY REFERENCES bugtracker.mantis_derivation_table.id',
				id_symbol		I    CONSTRAINTS 'FOREIGN KEY REFERENCES bugtracker.mantis_symbol_table.id',
				id_project I NOTNULL  CONSTRAINTS 'FOREIGN KEY REFERENCES bugtracker.mantis_project_table.id'
				",
				array( 'mysql' => 'DEFAULT CHARSET=utf8' ) )
			),

		array( 'CreateTableSQL', array( plugin_table( 'usecase_actor' ), "
				id		    	I		NOTNULL UNSIGNED AUTOINCREMENT PRIMARY,
				id_usecase I NOTNULL  CONSTRAINTS 'FOREIGN KEY REFERENCES bugtracker.mantis_plugin_honey_usecase_table.id',
				id_actor I NOTNULL  CONSTRAINTS 'FOREIGN KEY REFERENCES bugtracker.mantis_plugin_honey_actor_table.id'
				",
				array( 'mysql' => 'DEFAULT CHARSET=utf8' ) )
			),

		array( 'CreateTableSQL', array( plugin_table( 'scenario' ), "
				id		    	I		NOTNULL UNSIGNED AUTOINCREMENT PRIMARY,
				type			C(10)	NOTNULL DEFAULT \" '' \" , 
				steps 			B ,
				id_usecase      I       NOTNULL  CONSTRAINTS 'FOREIGN KEY REFERENCES bugtracker.mantis_plugin_honey_usecase_table.id' 
				",
				array( 'mysql' => 'DEFAULT CHARSET=utf8' ) )
			),

	     array( 'CreateTableSQL', array( plugin_table( 'rule' ), "
				id		    	I		NOTNULL UNSIGNED AUTOINCREMENT PRIMARY,
				name			C(50)	NOTNULL DEFAULT \" '' \" ,
				description		B ,
				id_project I NOTNULL  CONSTRAINTS 'FOREIGN KEY REFERENCES bugtracker.mantis_project_table.id'
				",
				array( 'mysql' => 'DEFAULT CHARSET=utf8' ) )
			),
			
		  array( 'CreateTableSQL', array( plugin_table( 'rule_usecase' ), "
				id		    	I		NOTNULL UNSIGNED AUTOINCREMENT PRIMARY,
				id_rule			I NOTNULL  CONSTRAINTS 'FOREIGN KEY REFERENCES bugtracker.mantis_rule_table.id',
				id_usecase		I NOTNULL  CONSTRAINTS 'FOREIGN KEY REFERENCES bugtracker.mantis_usecase_table.id'
				",
				array( 'mysql' => 'DEFAULT CHARSET=utf8' ) )
			),

		array( 'CreateTableSQL', array( plugin_table( 'file_usecase' ), "
				id		    	I		NOTNULL UNSIGNED AUTOINCREMENT PRIMARY,
				content			B ,
				filename		C(250),
				file_type		C(250),
				id_usecase		I NOTNULL  CONSTRAINTS 'FOREIGN KEY REFERENCES bugtracker.mantis_usecase_table.id'
				",
				array( 'mysql' => 'DEFAULT CHARSET=utf8' ) )
		),

	    array('CreateTableSQL',array(plugin_table('uc_note'),"
				id 			 I  NOTNULL UNSIGNED AUTOINCREMENT PRIMARY,
				id_uc 		 I NOTNULL  CONSTRAINTS 'FOREIGN KEY REFERENCES bugtracker.mantis_usecase_table.id',
				note 		 XL NOTNULL,
				reporter_id  I  UNSIGNED NOTNULL DEFAULT '0',
				view_state 		I2 NOTNULL DEFAULT '10',
				date_submitted 	T NOTNULL DEFAULT '0',
				last_modified 	T NOTNULL DEFAULT '0'
				",
				array( 'mysql' => 'DEFAULT CHARSET=utf8' ) )
		),
	    	
		
		/* array('AddColumnSQL',array(plugin_table('symbol'),"
				active	I1 NOTNULL DEFAULT '0'
				",
				array( 'mysql' => 'DEFAULT CHARSET=utf8' ) )
		),
		 
		 array('AddColumnSQL',array(plugin_table('synonymous'),"
				active	I1 NOTNULL DEFAULT '0'
				",
				array( 'mysql' => 'DEFAULT CHARSET=utf8' ) )
		),
		 
		 array('AddColumnSQL',array(plugin_table('impact'),"
				active	I1 NOTNULL DEFAULT '0'
				",
				array( 'mysql' => 'DEFAULT CHARSET=utf8' ) )
		),*/

		 array('AddColumnSQL',array(plugin_table('usecase'),"
				active	I1 NOTNULL DEFAULT '0'
				",
				array( 'mysql' => 'DEFAULT CHARSET=utf8' ) )
		),
					
		  array('AddColumnSQL',array(plugin_table('usecase_include'),"
				active	I1 NOTNULL DEFAULT '0'
				",
				array( 'mysql' => 'DEFAULT CHARSET=utf8' ) )
		),
		 
		  array('AddColumnSQL',array(plugin_table('usecase_extend'),"
				active	I1 NOTNULL DEFAULT '0'
				",
				array( 'mysql' => 'DEFAULT CHARSET=utf8' ) )
		),

		  array('AddColumnSQL',array(plugin_table('actor'),"
				active	I1 NOTNULL DEFAULT '0'
				",
				array( 'mysql' => 'DEFAULT CHARSET=utf8' ) )
		),
		
		    array('AddColumnSQL',array(plugin_table('usecase_actor'),"
				active	I1 NOTNULL DEFAULT '0'
				",
				array( 'mysql' => 'DEFAULT CHARSET=utf8' ) )
		),

		    array('AddColumnSQL',array(plugin_table('scenario'),"
				active	I1 NOTNULL DEFAULT '0'
				",
				array( 'mysql' => 'DEFAULT CHARSET=utf8' ) )
		),

			array('AddColumnSQL',array(plugin_table('rule'),"
				active	I1 NOTNULL DEFAULT '0'
				",
				array( 'mysql' => 'DEFAULT CHARSET=utf8' ) )
		),
		
			array('AddColumnSQL',array(plugin_table('uc_note'),"
				active	I1 NOTNULL DEFAULT '0'
				",
				array( 'mysql' => 'DEFAULT CHARSET=utf8' ) )
		),

			array('AddColumnSQL',array(plugin_table('derivation'),"
				id_project I NOTNULL  CONSTRAINTS 'FOREIGN KEY REFERENCES bugtracker.mantis_project_table.id'
				",
				array( 'mysql' => 'DEFAULT CHARSET=utf8' ) )
			),

);
	}//function


//ITEMS DE MENU DEL PLUGIN QUE SE INCORPORAN EN MANTIS

//	function print_menu_trace( ) {


		function print_menu_lel($t_links ) {
			
			$t_links = array();
			$t_page = plugin_page( 'indexLel' );
			$t_lang = plugin_lang_get( 'lel_link' );
			$t_links[] = "<a href=\"$t_page\">$t_lang</a>";

			$t_page = plugin_page( 'indexCU' );
			$t_lang = plugin_lang_get( 'usecase_link' );
			$t_links[] = "<a href=\"$t_page\">$t_lang</a>";
			
			return $t_links;


		}

	
	function include_js(){
	//$tpage=plugin_page('scripts.js');
	//$link_script="<script type='text/javascript' src=\"$tpage\"></script>";
	$tpage=   dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'pages\scripts.js';
	//$tpage='/mantisbt/plugins/Honey/pages/scripts.js';//con este anda
	//$link_script="<script type='text/javascript' src='/mantisbt/plugins/Honey/pages/scripts.js'></script>";

	$tpage=str_replace('\\', '/', $tpage);
	$words = preg_split('[/]', $tpage);
	$cant_words=sizeof($words);
	for($i=3;$i<$cant_words;$i++){$tpage2=$tpage2.'/'.$words[$i];}
	
	$link_script="<script type='text/javascript' src=\"$tpage2\"></script>";
	return $link_script;

	}


}//class
?>