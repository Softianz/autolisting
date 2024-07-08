<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the "site-content" div and all content after.
 *
 * @package WordPress
 * @subpackage Listdo
 * @since Listdo 1.0
 */
$footer = apply_filters( 'listdo_get_footer_layout', 'default' );
?>

	</div><!-- .site-content -->

	<footer id="apus-footer" class="apus-footer <?php echo esc_attr( (!empty($footer))?'':'footer-default' ); ?>" role="contentinfo">
		<?php if ( !empty($footer) ): ?>
			<?php listdo_display_footer_builder($footer); ?>
		<?php else: ?>
			<div class="apus-copyright">
				<div class="container">
					<div class="copyright-content">
						<div class="text-copyright text-center">
							<?php
											
								$allowed_html_array = array( 'a' => array('href' => array()) );
								echo wp_kses(sprintf(__('&copy; %s - Listdo. All Rights Reserved. <br/> Powered by <a href="//apusthemes.com">ApusTheme</a>', 'listdo'), date("Y")), $allowed_html_array);
							?>

						</div>
						
					</div>
				</div>
			</div>
		<?php endif; ?>

		<?php if ( listdo_get_config('back_to_top') ) { ?>
			<a href="#" id="back-to-top">
				<i class="flaticon-up"></i>
			</a>
		<?php } ?>
		
	</footer><!-- .site-footer -->
</div><!-- .site -->
<?php wp_footer(); ?>
</body>
</html>