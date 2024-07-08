<?php
$views = intval(get_post_meta($job->ID, '_listing_views_count', true));
$updated_date = get_the_modified_time(get_option('date_format'), $job);
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<style>
  .listing-image {
    padding: 0px !important;
    margin-right: 15px;
    border-radius: 0px !important;
  }
</style>
<div class="my-listing-item-wrapper job_listing ">
  <div class="row flex-middle-sm">
    <div class="col-md-6 col-sm-9 col-xs-12">
      <div class="flex-middle">
        <?php
        if (has_post_thumbnail($job->ID)) {
        ?>
          <div class="listing-image">
            <div class="listing-image-inner">
              <?php
              $linkable = false;
              if ($job->post_status == 'publish') {
                $linkable = true;
              }
              listdo_display_listing_cover_image('listdo-image-mylisting', $linkable, $job);
              ?>

            </div>
          </div>
        <?php } ?>
        <div class="listing-content">
          <?php listdo_display_listing_review($job); ?>
          <h3 class="listing-title">
            <?php if ($job->post_status == 'publish') : ?>
              <a href="<?php echo get_permalink($job->ID); ?>"><?php echo trim($job->post_title); ?></a>
            <?php else : ?>
              <?php echo trim($job->post_title); ?>
            <?php endif; ?>
          </h3>
          <?php listdo_listing_tagline($job); ?>
          <div class="meta-listing">
            <?php listdo_display_listing_first_category($job); ?>
            <?php listdo_display_listing_location($job); ?>
            <?php listdo_display_listing_phone($job); ?>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-2">
      <?php
      global $wpdb;
      $table_name = $wpdb->prefix . 'paypal_transaction';
      $myrows = $wpdb->get_results("SELECT * FROM " . $table_name . " WHERE user_id = " . get_current_user_id() . " AND job_id= " . $job->ID . " ");
      if (isset($myrows) && !empty($myrows)) {
      ?>
        <div style="position: relative;
    text-align: center;
    display: inline;">
          <button id="downloadQRCODENow" style="position: absolute;
      position: absolute;
    bottom: -32px;
    background: #000;
    border: unset;
    font-size: 12px;
    padding: 5px 20px;
    border-radius: 0px;
    width: 100%;" data-link="<?php echo get_permalink($job->ID) ?>" class="btn btn-success download-btn_<?= $job->ID ?>">Download QR </button>

          <div class="qrcode-container_new_<?= $job->ID ?>">
            <div class="qrcode-container" style="background: #feee03;
    text-align: center;
    padding: 0px 0px;">
              <div style="    height: 20px;
    background: #23fb09;
    border-bottom-right-radius: 50%;
    border-bottom-left-radius: 50%;"> <span style="width: 50%;
    height: 2px;
    background: #000;
    display: block;
    margin: 0 auto;
    position: relative;
    top: 9px;"></span></div>
              <div style="    background: #feee03;
    font-size: 60px;
    font-family: sans-serif;
    color: red;
    font-weight: 900;
    line-height: 50px;
    margin-bottom: 5px;
    padding-top: 11px;;">
                <div>For</div>
                <div>Sale!</div>
              </div>
              <div>
                <img style="    width: 93%;
    border: 10px solid #23fb09;" src="data:image/png;base64,<?= $myrows[0]->qr ?>">
              </div>

            </div>
          </div>
        </div>
      <?php
      } else {
      ?>
        <button id="downloadQRCODE" data-toggle="modal" data-target="#myModal_<?= $job->ID ?>" data-link="<?php echo get_permalink($job->ID) ?>" data-list_id="<?= $job->ID ?>" class="btn btn-primary">Pay To Download QR</button>
      <?php
      }
      ?>

    </div>
    <div class="col-md-4 col-sm-3 col-xs-12 ali-right">
      <div class="right-inner">
        <?php
        $actions = array();
        switch ($job->post_status) {
          case 'publish':
            $actions['edit'] = array('label' => '<i class="ti-pencil"></i>' . esc_html__('Edit', 'listdo'), 'nonce' => false);
            break;
          case 'expired':
            if (job_manager_get_permalink('submit_job_form')) {
              $actions['relist'] = array('label' => '<i class="ti-pencil"></i>' . esc_html__('Relist', 'listdo'), 'nonce' => true);
            }
            break;
          case 'pending_payment':
          case 'pending':
            if (job_manager_user_can_edit_pending_submissions()) {
              $actions['edit'] = array('label' => '<i class="ti-pencil"></i>' . esc_html__('Edit', 'listdo'), 'nonce' => false);
            }
            break;
          case 'draft':
          case 'preview':
            $actions['continue'] = array('label' => '<i class="ti-pencil"></i>' . esc_html__('Continue Submission', 'listdo'), 'nonce' => true);
            break;
        }

        $actions['delete'] = array('label' => '<i class="ti-trash"></i>' . esc_html__('Delete', 'listdo'), 'nonce' => true);
        $actions = apply_filters('job_manager_my_job_actions', $actions, $job);

        foreach ($actions as $action => $value) {
          $action_url = add_query_arg(array('action' => $action, 'job_id' => $job->ID));
          if ($value['nonce']) {
            $action_url = wp_nonce_url($action_url, 'job_manager_my_job_actions');
          }
          echo '<a href="' . esc_url($action_url) . '" class="job-dashboard-action-' . esc_attr($action) . '">'
            . trim($value['label']) . '</a>';
        }
        ?>
        <div class="listing-status">
          <div class="btn-status btn-status-<?php echo esc_attr($job->post_status); ?>">
            <?php
            $post_status = get_post_status_object($job->post_status);
            if (!empty($post_status->label)) {
              echo esc_html($post_status->label);
            } else {
              echo esc_html($post->post_status);
            }
            ?>
          </div>
        </div>
        <!-- SOLD OUT BUTTON  START HERE -->
        <div class="container">
          <!-- Trigger the modal with a button -->
          <button type="button" class="btn btn-info btn-lg" onclick="SoldOut(this, <?= $job->ID ?> )">Sold Out</button>
        </div>
      </div>
    </div>
  </div>
</div>
<script src="https://www.paypal.com/sdk/js?client-id=AaV5LkjAdEcgFe7uXbMu0e4ZoYQb-Sgx0B_GDcT4p_R7cTBHbGDs2tuhfBj2Mti0NVD4c9IFvhjuVPTg"></script>

<!-- Modal -->
<div id="myModal_<?= $job->ID ?>" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Information</h4>
      </div>
      <div class="modal-body">
        <p>Before Download The QR Code You Must Pay</p>
        <p>Get Access to your QR Code Now!</p>
        <div id="paypal-button-container_<?= $job->ID ?>"></div>
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
          let JobId = <?php echo $job->ID ?>;
          let jobLink = "<?php echo get_permalink($job->ID) ?>";

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
              if (response.success) {
                alert(response.data);
                location.reload()
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
    }).render('#paypal-button-container_<?= $job->ID ?>');

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
    $('.download-btn_<?= $job->ID ?>').click(function() {
      html2canvas(document.querySelector(".qrcode-container_new_<?= $job->ID ?>")).then(canvas => {
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
  });
</script>