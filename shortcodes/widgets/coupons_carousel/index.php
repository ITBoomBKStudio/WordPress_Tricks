<?php

function couponsCarousel(){

    //wp_enqueue_script('couponsCarousel', bkstudio_widget_assets_directory('couponsCarousel') . 'couponsCarousel.js', ['jquery'], '1.0.0', true);
    wp_enqueue_style('couponsCarousel', bkstudio_widget_assets_directory('couponsCarousel') . 'couponsCarousel.css', [], '1.0.0', 'all');

    $args = array(
        'posts_per_page'   => -1,
        'orderby'          => 'title',
        'order'            => 'desc',
        'post_type'        => 'shop_coupon',
        'post_status'      => 'publish',
        'orderby'   => 'meta_value',
        'order' => 'DESC',
    );

    $current_user = wp_get_current_user();
        
    $coupons = get_posts( $args );
        
    $coupon_names = array();
    foreach ( $coupons as $coupon ) {
        $coupon_name = $coupon->post_title;
        array_push( $coupon_names, $coupon_name );
    }

    ob_start();

?>

    <div class="coupons__list-wrapper">

        <ul class="coupons__list">
            <?php
            foreach ($coupon_names as $key => $value):  
            global $woocommerce;
            $coupon = new WC_Coupon($value);
            $emails = $coupon->get_email_restrictions();
            $amount = $coupon->get_amount();
            $date = $coupon->get_date_expires();

            $display = false;
            if(
                sizeof($emails)<1 ||
                (sizeof($emails)>0 && in_array($current_user->user_email,$emails))
                
                ){
                $display = true;
            }
            if($display):
            ?>
                <li class="coupon-card">
                        <span class="coupon-card__amount"><?php echo '-'.$amount.'%'; ?></span>
                        <span class="coupon-card__value"><?php echo $value; ?></span>
                        <?php if($date): ?>
                            <span class="coupon-card__date"><?php echo 'До '.$date->date("d.m.Y"); ?></span>
                        <?php else: ?>
                            <span class="coupon-card__date"><?php _e('Без дати завершення','nutsboom'); ?></span>
                        <?php endif; ?>
                </li>
            <?php  
            endif;            
            endforeach;
            ?>
        </ul>
    </div>

<?php
    
    return ob_get_clean();
}

add_shortcode('couponsCarousel', 'couponsCarousel');
?>
