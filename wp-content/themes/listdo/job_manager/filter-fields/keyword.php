<?php
$search_keywords = isset( $_REQUEST['search_keywords'] ) ? $_REQUEST['search_keywords'] : $keywords;
?>
<div class="search_keywords">
	<input class="form-control style2" type="text" name="search_keywords" placeholder="<?php esc_attr_e('Keywords...', 'listdo'); ?>" value="<?php echo esc_attr( $search_keywords ); ?>" />
</div>