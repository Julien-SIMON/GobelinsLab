<li class="dropdown messages-menu">
	<a href="#" class="dropdown-toggle" data-toggle="dropdown">
	<i class="icon iconastic-globe-world"></i>
	</a>
	<ul class="dropdown-menu">
		<li class="header"><?php echo _('#core#_#1#'); ?></li>
<?php
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
						loc.deleted_date = 0
						ORDER BY loc.long_name ASC"); 
$q0->execute();
while( $r0 = $q0->fetch(PDO::FETCH_OBJ) )
{
	echo '
		<li>
			<ul class="menu">
				<li>
					<a href="index.php?lang='.$r0->SHORT_NAME.'">
					<div class="pull-left">
						<div class="mini-flags '.$r0->FLAG_PATH.'"></div>
					</div>
					<h4>
					'.$r0->LONG_NAME.'
					</h4>
					</a>
				</li>
			</ul>
		</li>
	';
}
?>
	</ul>
</li>