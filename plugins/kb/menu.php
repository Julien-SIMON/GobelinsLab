<li class="treeview">
	<a href="#">
	<i class="icon iconastic-lightbulb-1"></i>
	<span>Knowledge base</span>
	<i class="icon iconastic-angle-left pull-right"></i>
	<!-- <span class="label label-primary pull-right">4</span> -->
	</a>
	<ul class="treeview-menu">
		<li><a href="index.php?g=kb&p=document"><i class="icon iconastic-pencil"></i> Rédiger</a></li>


<?php
$q0=get_link()->prepare("SELECT id AS ID,name AS NAME FROM ".get_ini('BDD_PREFIX')."kb_class WHERE deleted_date=0");
$q0->execute(array());
while($r0 = $q0->fetch(PDO::FETCH_OBJ)) {
	echo '		<li><a href="index.php?g=kb&p=doc&class='.$r0->NAME.'"><i class="icon iconastic-unlock"></i> '.$r0->NAME.'</a></li>';
}
?>

	</ul>
</li>