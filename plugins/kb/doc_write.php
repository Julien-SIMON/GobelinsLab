
<?php
switch ($a) {
	case 'dbInstanceCredsUpdate':
		if(isset($_GET['dbInstanceId'])){$dbInstanceId=$_GET['dbInstanceId'];}elseif(isset($_POST['dbInstanceId'])){$dbInstanceId=$_POST['dbInstanceId'];}else{
			// TODO
			exit(100);
		}
		if(isset($_GET['login'])){$login=$_GET['login'];}elseif(isset($_POST['login'])){$login=$_POST['login'];}else{$login='';}
		if(isset($_GET['password'])){$password=$_GET['password'];}elseif(isset($_POST['password'])){$password=$_POST['password'];}else{$password='';}
		
		$dbInstance = new dbInstance($dbInstanceId);
		
		$dbInstance->updateCredentials($login,$password,'','');
				
		// TODO - add result
		echo '<script>location.reload();</script>';
	break;
	default:
		echo '
<form id="">
<label for="text-basic">Title:</label>
<input name="text-basic" id="text-basic" value="" type="text">
<label for="text-basic">Subtitle:</label>
<input name="text-basic" id="text-basic" value="" type="text">

</form>
<div class="mini-flags icon-libflags-mh"></div>
		';

		if ($handle = opendir('lib/flags/svg/')) {
		
		    while (false !== ($entry = readdir($handle))) {
		
		        if ($entry != "." && $entry != "..") {
		$name = strtolower(substr($entry,0,2));
/*
echo '
.ui-icon-libflags-'.$name.':after {<BR>
	background-image: url("lib/flags/svg/'.strtoupper($name).'.svg");<BR>
}<BR><BR>
<BR>
.ui-nosvg .ui-icon-libflags-'.$name.':after {<BR>
	background-image: url("lib/flags/png/256/'.strtoupper($name).'.png");<BR>
}<BR><BR><BR>
';

echo '
.icon-libflags-'.$name.' {<BR>
	background-image: url(png/256/'.strtoupper($name).'.png);<BR>
	background-image: url(svg/'.strtoupper($name).'.svg), none;<BR>
}<BR><BR>
';
*/
echo '<div class="mini-flags icon-libflags-'.$name.'"></div>';
		        }
		    }
		
		    closedir($handle);
		}

    break;
}

echo '
<div class="btn-group btn-group-justified" role="group" aria-label="...">
  <div class="btn-group" role="group" style="width: 5.5em;">
    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      Class
      <span class="caret"></span>
    </button>
    <ul class="dropdown-menu">
		<li><a href="#">Add</a></li>
		<li role="separator" class="divider"></li>
';
$q0=get_link()->prepare("SELECT id AS ID,name AS NAME FROM ".get_ini('BDD_PREFIX')."kb_class WHERE deleted_date=0");
$q0->execute(array());
while($r0 = $q0->fetch(PDO::FETCH_OBJ)) {
	echo '	<li><a href="index.php?g=kb&p=doc&class='.$r0->NAME.'" data-ajax="false">'.$r0->NAME.'</a></li>';
}
echo '
	</ul>
  </div>
  

  <button type="button" class="btn btn-default">1</button>
  <button type="button" class="btn btn-default">2</button>
</div>
';
?>
<BR><BR>
<div id="summernote"><p>Hello Summernote</p></div>
<script>
  $(document).ready(function() {
      $('#summernote').summernote();
  });
</script>