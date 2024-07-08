<?php
global $post;
$price_from = get_post_meta($post->ID, '_job_price_from', true);
$isOBO = get_post_meta($post->ID, '_custom-radio-26', true);

?>

<div class="widget widget-meeting">
	<h2 class="widget-title">
		<span><?php esc_html_e('Schedule Meeting', 'listdo'); ?></span>
	</h2>
	<form method="post" action="?" id="scheduleMeeting">
		<div class="row">
			<div class="col-sm-12">
				<div class="form-group">
					<label>Select Date</label>
					<input type="date" min="<?php echo date("Y-m-d"); ?>" class="form-control" onchange="getTimming(this, <?= get_the_ID(); ?>)" name="meeting_date" placeholder="<?php esc_attr_e('Select Date', 'listdo'); ?>" required="required">
				</div>
			</div>
			<div class="col-sm-12">
				<div class="form-group">
					<label>Select Time</label>
					<input disabled type="time" class="form-control" id="meeting_time" name="meeting_time" placeholder="<?php esc_attr_e('Select time', 'listdo'); ?>" required="required">
					<small class="timeingmsg text-success"></small>
				</div>
			</div>
			<?php if ($isOBO == 'OBO (Offer Best Price)') { ?>
				<div class="col-sm-12">
					<div class="form-group">
						<label>Offer Price</label>
						<input type="number" class="form-control" min="<?= $price_from + 1; ?>" name="offer_price" placeholder="<?php esc_attr_e('Offer Price', 'listdo'); ?>" required="required">
					</div>
				</div>
			<?php  } ?>

		</div>
		<input type="hidden" id="post_id" name="post_id" value="<?= get_the_ID(); ?>">
		<input type="hidden" id="post_author" name="post_author" value="<?= $post->post_author; ?>">
		<button class="button btn btn-theme border-2 schedulebutton"><?php echo esc_html__('Submit', 'listdo'); ?></button>
		<div class="responsemsg"></div>
	</form>
</div>