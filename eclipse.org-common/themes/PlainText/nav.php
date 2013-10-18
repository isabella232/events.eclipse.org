		<td width="200" valign="top">

			<table width="100%" cellpadding="1" cellspacing="0" border="0" class="nav">
<?php
	for($i = 0; $i < $Nav->getLinkCount(); $i++) {
		$Link = $Nav->getLinkAt($i);
?>				
				<tr>
					<td nowrap="true" class="nav_separator" height="1"></td>
				</tr>
				<tr>
					<td nowrap="true"><a class="nav_item" href="<?= $Link->getURL() ?>" target="<?= $Link->getTarget() ?>"><?= $Link->getText() ?></a></td>
				</tr>
<?php
	}	
?>
				<tr>
					<td nowrap="true" class="nav_separator" height="1"></td>
				</tr>
			</table>
		</td>
		
		<td valign="top" class="normal">