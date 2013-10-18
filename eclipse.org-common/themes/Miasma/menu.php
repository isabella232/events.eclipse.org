	<ul id="topnav">
<?php
	$nextclass = "";
	
	for($i = 0; $i < $Menu->getMenuItemCount(); $i++) {
		$MenuItem = $Menu->getMenuItemAt($i);
		

		$aclass = "";		
		if(strpos($_SERVER['SCRIPT_FILENAME'], $MenuItem->getURL())) {
			$aclass 		= "class=\"tabselected\"";
		}
		
		
?>
		<li><a <?= $aclass ?>" href="<?= $MenuItem->getURL() ?>" target="<?= $MenuItem->getTarget() ?>"><?= $MenuItem->getText() ?></a></li>
<?php
	}
?>			
	</ul>
</div>