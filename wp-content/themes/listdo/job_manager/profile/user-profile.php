<?php
$user = (isset($_GET['author_name'])) ? get_user_by('slug', $author_name) : get_userdata(intval($author));
$data = get_userdata( $user->ID );

$birthday = get_the_author_meta( 'apus_birthday', $user->ID );
$sex = get_the_author_meta( 'apus_sex', $user->ID );
$marital_status = get_the_author_meta( 'apus_marital_status', $user->ID );
$phone = !empty($data->phone) ? $data->phone : '';
$email = !empty($data->user_email) ? $data->user_email : '';
$address = get_the_author_meta( 'apus_address', $user->ID );
$url = !empty($data->url) ? $data->url : '';
?>
<div class="user-profile">
	<div class="box-list">
		<h3 class="title"><i class="flaticon-house"></i><?php esc_html_e( 'About me', 'listdo' ); ?></h3>
		<div class="box-inner">
			<?php if ( !empty($data->description) ) { ?>
				<div class="box-content"><?php echo esc_html($data->description); ?></div>
			<?php } ?>
			<ul class="list-information">
				<li><label><?php esc_html_e( 'Name', 'listdo' ); ?></label> <span><?php echo trim($data->display_name); ?></span></li>
				<?php if ( !empty($birthday) ) { ?>
					<li><label><?php esc_html_e( 'Birthday', 'listdo' ); ?></label> <span><?php echo esc_attr($birthday); ?></span></li>
				<?php } ?>
			</ul>
			<?php if ( !empty($phone) || !empty($email) || !empty($address) || !empty($url) ) { ?>
				<ul class="list-information">
					<?php if ( !empty($phone) ) { ?>
						<li><label><?php esc_html_e( 'Phone', 'listdo' ); ?></label> <span><?php echo esc_attr($phone); ?></span></li>
					<?php } ?>
					<?php if ( !empty($email) ) { ?>
						<li><label><?php esc_html_e( 'Email', 'listdo' ); ?></label> <span><?php echo esc_attr($email); ?></span></li>
					<?php } ?>
					<?php if ( !empty($address) ) { ?>
						<li><label><?php esc_html_e( 'Address', 'listdo' ); ?></label> <span><?php echo esc_attr($address); ?></span></li>
					<?php } ?>
					<?php if ( !empty($url) ) { ?>
						<li><label><?php esc_html_e( 'Website', 'listdo' ); ?></label> <span><?php echo esc_attr($url); ?></span></li>
					<?php } ?>
				</ul>
			<?php } ?>
			<?php 
				$options = listdo_user_social_defaults();
				$socials = get_user_meta( $user->ID, 'apus_socials', true );
				$value1 = array();
				foreach ($socials as $key1 => $label1) {
					if(!empty($label1)){
						$value1[] = $label1;
					}
				}
			?>
			<?php if(count($value1)>0){ ?>
				<div class="user-social">
					<ul class="spcials">
					<?php
						foreach ($options as $key => $label) {
							$value = isset($socials[$key]) ? $socials[$key] : '';
							if ( $value ) {
								?>
								<li><a href="<?php echo esc_url($value); ?>" class="<?php echo esc_attr($key); ?>" title="<?php echo esc_attr($label); ?>"><?php echo esc_html($key); ?></a></li>
								<?php
							}
						}
					?>
					</ul>
				</div>
			<?php } ?>
		</div>
	</div>
</div>