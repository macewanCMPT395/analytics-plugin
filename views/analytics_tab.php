<ul>
<?php
	foreach( $menu_items as $item )
	{
			$active = ($this == $item['page']) ? ' class="active"' : '';
			echo '<li><a href="'.$item['url'].'"'.$active.'>'.$item['name'].'</a></li>';
	}
?>
</ul>
