<?php
// ------------------------------------------------------------------- //
// Add this statements only on the admin_plugins and setup page. This check if the current user is in the admins group
// ------------------------------------------------------------------- //
$groupM = new groupManager();
// TODO - $user = new user($_SESSION['USER_ID']);
// TODO - if(!isset($user->groupIdArray)||!in_array($groupM->getId('admins'),$user->groupIdArray)){if($m!='j'){include('plugins/core/403.php');exit(403);}}
// ------------------------------------------------------------------- //

include('lib/getTextCompiler/php-mo.php');

// Generate the locale directories
$q0=get_link()->prepare("SELECT 
									loc.id AS ID,
									loc.short_name AS SHORT_NAME,
									loc.long_name AS LONG_NAME,
									loc.flag_path AS FLAG_PATH,
									loc.created_date AS CREATED_DATE,
									loc.created_id AS CREATED_ID,
									loc.edited_date AS EDITED_DATE,
									loc.edited_id AS EDITED_ID,
									loc.deleted_date AS DELETED_DATE,
									loc.deleted_id AS DELETED_ID
								FROM 
								".get_ini('BDD_PREFIX')."core_locale loc
								WHERE 
								loc.deleted_date=0
								ORDER BY loc.long_name ASC"); 
$q0->execute();
while( $r0 = $q0->fetch(PDO::FETCH_OBJ) )
{
	if(!is_dir('locale/'.$r0->SHORT_NAME)){mkdir('locale/'.$r0->SHORT_NAME);}
	if(!is_dir('locale/'.$r0->SHORT_NAME.'/LC_MESSAGES')){mkdir('locale/'.$r0->SHORT_NAME.'/LC_MESSAGES');}
	if(is_file('locale/'.$r0->SHORT_NAME.'/LC_MESSAGES/lang.po')){unlink('locale/'.$r0->SHORT_NAME.'/LC_MESSAGES/lang.po');}
	if(is_file('locale/'.$r0->SHORT_NAME.'/LC_MESSAGES/lang.mo')){unlink('locale/'.$r0->SHORT_NAME.'/LC_MESSAGES/lang.mo');}
}

// Generate po files
$q0=get_link()->prepare("SELECT 
									trans.id AS ID,
									trans.id_plugin AS ID_PLUGIN,
									trans.id_locale AS ID_LOCALE,
									pg.name AS PLUGINNAME,
									loc.short_name AS SHORT_NAME,
									trans.index_translation AS INDEX_TRANSLATION,
									trans.translation AS TRANSLATION,
									trans.comment AS COMMENT,
									trans.created_date AS CREATED_DATE,
									trans.created_id AS CREATED_ID,
									trans.edited_date AS EDITED_DATE,
									trans.edited_id AS EDITED_ID,
									trans.deleted_date AS DELETED_DATE,
									trans.deleted_id AS DELETED_ID
								FROM 
								".get_ini('BDD_PREFIX')."core_translation trans ,
								".get_ini('BDD_PREFIX')."core_plugins pg ,
								".get_ini('BDD_PREFIX')."core_locale loc
								WHERE 
								trans.id_plugin=pg.id AND
								trans.id_locale=loc.id AND
								loc.deleted_date=0 AND
								pg.deleted_date=0 AND
								trans.deleted_date=0
								ORDER BY loc.short_name ASC,trans.index_translation ASC,pg.name ASC"); 
$q0->execute();
while( $r0 = $q0->fetch(PDO::FETCH_OBJ) )
{
	file_put_contents(
		'locale/'.$r0->SHORT_NAME.'/LC_MESSAGES/lang.po',
		'#: '.$r0->COMMENT."\n".'msgid "#'.$r0->PLUGINNAME.'#_#'.$r0->INDEX_TRANSLATION.'#"'."\n".'msgstr "'.$r0->TRANSLATION.'"'."\n\n"
		, FILE_APPEND);
}

foreach (glob("locale/*/*/*.po") as $poFileName) {
	phpmo_convert( $poFileName, str_replace('.po','.mo',$poFileName) );
}
?>