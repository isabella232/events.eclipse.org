			<table width="100%" cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td width="100%" class="menu_border" height="1"></td>
				</tr>
			</table>
			<table width="100%" cellpadding="1" cellspacing="0" border="0" class="menu">
				<tr>
					<td class="menu_border" width="1"></td>
<?php
	for($i = 0; $i < $Menu->getMenuItemCount(); $i++) {
		$MenuItem = $Menu->getMenuItemAt($i);
?>				
							<td>&#160;&#160;&#160;</td>
							<td nowrap="true"><a class="menu_item" href="<?= $MenuItem->getURL() ?>" target="<?= $MenuItem->getTarget() ?>"><?= $MenuItem->getText() ?></a></td>
							<td>&#160;&#160;&#160;</td>
							<td class="menu_separator" width="1"></td>
				
<?php
	}
?>
						<td width="100%"></td>
						<td class="menu_border" width="1"></td>
					</tr>
				</table>
		<?php
		# This table closed in the footer 
		?>
		<table width="100%" cellpadding="0">
			<tr>
		

