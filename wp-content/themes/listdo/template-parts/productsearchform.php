<?php if ( defined('LISTDO_WP_JOB_MANAGER_ACTIVED') && LISTDO_WP_JOB_MANAGER_ACTIVED ): ?>

	<div class="apus-search-form ">
		<div class="searchform-header">
			<form action="<?php echo esc_url( home_url( '/' ) ); ?>" method="get">
			  	<div class="input-group">
			  		<input type="text" placeholder="<?php esc_attr_e( 'Search', 'listdo' ); ?>" name="s" class="apus-search form-control"/>
			  	</div>
				<input type="hidden" name="post_type" value="job_listing" class="post_type" />
			</form>
		</div>
	</div>
<?php endif; ?>