<?php
/**
* Template Name: Home Page
*
* @package WordPress
* @subpackage listdo
*/

use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

\Elementor\Plugin::$instance->frontend->add_body_class( 'elementor-template-canvas' );

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<?php if ( ! current_theme_supports( 'title-tag' ) ) : ?>
		<title><?php echo wp_get_document_title(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></title>
	<?php endif; ?>
	<?php wp_head(); ?>
	<?php

	// Keep the following line after `wp_head()` call, to ensure it's not overridden by another templates.
	Utils::print_unescaped_internal_string( Utils::get_meta_viewport( 'canvas' ) );
	?>
</head>
<body <?php body_class(); ?>>
	<?php
	Elementor\Modules\PageTemplates\Module::body_open();
	/**
	 * Before canvas page template content.
	 *
	 * Fires before the content of Elementor canvas page template.
	 *
	 * @since 1.0.0
	 */
	do_action( 'elementor/page_templates/canvas/before_content' );

	\Elementor\Plugin::$instance->modules_manager->get_modules( 'page-templates' )->print_content();

	/**
	 * After canvas page template content.
	 *
	 * Fires after the content of Elementor canvas page template.
	 *
	 * @since 1.0.0
	 */
	do_action( 'elementor/page_templates/canvas/after_content' );
	?>
        <button id="downloadQRCODE" style="display: block; margin: 0px auto; width: 24%; margin-bottom: 10px; background-color:#F88500; color: #FFFFFF; text-align: center;" data-toggle="modal" data-target="#myModal_register" data-link="https://www.softianz.com/autolisting2/listing/yoga-food-shop/" data-list_id="531" class="btn btn-primary">ORDER NOW</button>
	<div class="elementor-element elementor-element-95db022 e-grid e-con-boxed e-con e-parent" data-id="95db022" data-element_type="container" style="background-color: #F88500;
    width: 100% !important; text-align: center; display: block;" data-settings="{&quot;background_background&quot;:&quot;classic&quot;,&quot;content_width&quot;:&quot;boxed&quot;}" data-core-v316-plus="true">
					<div class="e-con-inner">
				<div class="elementor-element elementor-element-3b93f1a elementor-widget elementor-widget-text-editor" data-id="3b93f1a" data-element_type="widget" data-widget_type="text-editor.default">
				<div class="elementor-widget-container">
							<p>&nbsp;AI Smart Signs | All Right Reserved | 2024</p>						</div>
				</div>
					</div>
				</div>
	<?php
	wp_footer();
	?>
	</body>
</html>





<script src="https://www.paypal.com/sdk/js?client-id=AaV5LkjAdEcgFe7uXbMu0e4ZoYQb-Sgx0B_GDcT4p_R7cTBHbGDs2tuhfBj2Mti0NVD4c9IFvhjuVPTg"></script>

<!-- Modal -->
<div id="myModal_register" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Information</h4>
      </div>
      <div class="modal-body">
        <p>Enter Your Email Address</p>
        <input type="text" name="email" id="email" /><button id="pay-but" type="button" class="btn btn-default">PAY NOW</button>
        <div id="paypal-button-container_register" style="display:none;"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>


<script>
      document.addEventListener('DOMContentLoaded', function() {
        paypal.Buttons({
          createOrder: function(data, actions) {
            return actions.order.create({
              purchase_units: [{
                amount: {
                  value: '5.00'
                }
              }]
            });
          },
          onApprove: function(data, actions) {
            return actions.order.capture().then(function(details) {
              // Extract transaction details
              const transaction_id = details.id;
              const amount = details.purchase_units[0].amount.value;
              const payer_name = details.payer.name.given_name;
              let JobId = 'register';
              let jobLink = "https://www.softianz.com/autolisting2/";

              // Perform AJAX request to update the database
              jQuery.ajax({
                url: '<?php echo admin_url('admin-ajax.php'); ?>',
                method: 'POST',
                data: {
                  action: 'handle_paypal_approval',
                  nonce: '<?php echo wp_create_nonce('paypal_nonce'); ?>',
                  transaction_id: transaction_id,
                  amount: amount,
                  payer_name: payer_name,
                  JobId: JobId,
                  jobLink: jobLink
                },
                success: function(response) {
                  alert(JSON.stringify(response));
                  if (response.success) {
                    console.log(response);
                    // alert(jQuery("#email").val());
                  //  alert(response.data.transaction_id);
                    // location.reload()
                    location.href = "https://www.softianz.com/autolisting2/send-invite.php?registrant="+jQuery("#email").val();
                  } else {
                    alert('Database update failed: ' + response.data);
                  }
                },
                error: function(xhr, status, error) {
                  console.error('AJAX error:', status, error);
                  alert('AJAX error: ' + error);
                }
              });
            });
          }
        }).render('#paypal-button-container_register');

      });
    </script>
    
      <script>
        // jQuery(document).ready(function($) {
        //     $('.download-btn').click(function() {
        //         printDiv('.qrcode-container_new');
        //     });
        // });

        // function printDiv(divId) {
        //     var divToPrint = document.querySelector(divId);
        //     var newWin = window.open('', '_blank');
        //     newWin.document.open();
        //     newWin.document.write('<html><head><title>Print</title><style>body{font-family: Arial, sans-serif;}#' + divId + ' {border: 1px solid #000; padding: 20px;}</style></head><body>');
        //     newWin.document.write(divToPrint.outerHTML);
        //     newWin.document.write('</body></html>');
        //     newWin.document.close();
        //     newWin.print();
        //     newWin.close();
        // }
    </script>
    <script>
        jQuery(document).ready(function($) {
            $('.download-btn_register').click(function() {
                html2canvas(document.querySelector(".qrcode-container_new_register")).then(canvas => {
                    // Convert canvas to base64 image
                    var imgData = canvas.toDataURL('image/png');
                    // Create a temporary link element
                    var link = document.createElement('a');
                    link.href = imgData;
                    link.download = 'div-image.png';
                    // Append the link to the body
                    document.body.appendChild(link);
                    // Trigger the download
                    link.click();
                    // Remove the link from the document
                    document.body.removeChild(link);
                });
            });
            $('#pay-but').click(function() {
              $("#paypal-button-container_register").toggle();
            });
            
        });
    </script>
