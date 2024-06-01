<?php
/*
Plugin Name: 01 AVID Installment Price Field 2.0
Description: Custom Installment Price Field compatibility for 2C2P
Requires Plugins: woocommerce, 2c2p-redirect-api-for-woocommerce
Version: 1.2
Author: AVID-MIS
Author URI: www.avid.com.ph
*/
// Include the admin file
require_once(plugin_dir_path(__FILE__) . 'sidebar_dashboard.php');
//!==========ERRORS============


//*===========================================INSTALLMENT FIELD ON POST========================================
// Add installation price field to single product
function custom_woocommerce_add_installation_price_field() {
    woocommerce_wp_text_input(
        array(
            'id' => '_installation_price',
            'label' => __('Installment Price', 'woocommerce'),
            'data_type' => 'price',
        )
    );
    woocommerce_wp_text_input(
        array(
            'id' => '_installation_start_date',
            'label' => __('Installment Start Date', 'woocommerce'),
            'data_type' => 'date',
        )
    );

    // Add End Date field with datepicker
    woocommerce_wp_text_input(
        array(
            'id' => '_installation_end_date',
            'label' => __('Installment End Date', 'woocommerce'),
            'data_type' => 'date',
        )
    );
}
    
add_action('woocommerce_product_options_pricing', 'custom_woocommerce_add_installation_price_field');

// Save installation price for single product
function custom_woocommerce_save_installation_price($product_id) {
    $installation_price = isset($_POST['_installation_price']) ? sanitize_text_field($_POST['_installation_price']) : '';
    $start_date = isset($_POST['_installation_start_date']) ? sanitize_text_field($_POST['_installation_start_date']) : '';
    $end_date = isset($_POST['_installation_end_date']) ? sanitize_text_field($_POST['_installation_end_date']) : '';

    update_post_meta($product_id, '_installation_start_date', $start_date);
    update_post_meta($product_id, '_installation_end_date', $end_date);


    update_post_meta($product_id, '_installation_price', $installation_price);
    
}

add_action('woocommerce_process_product_meta', 'custom_woocommerce_save_installation_price');

// Add installation price field to variable product variations
// Add installation price field to variable product variations
function custom_woocommerce_add_variation_installation_price_field($loop, $variation_data, $variation) {
    woocommerce_wp_text_input(
        array(
            'id' => '_installation_price[' . $variation->ID . ']',
            'label' => __('Installment Price', 'woocommerce'),
            'data_type' => 'price',
            'value' => get_post_meta($variation->ID, '_installation_price', true),
        )
    );

    // Add Start Date field with datepicker
    woocommerce_wp_text_input(
        array(
            'id' => '_installation_start_date[' . $variation->ID . ']',
            'label' => __('Installment Start Date', 'woocommerce'),
            'data_type' => 'date',
            'value' => get_post_meta($variation->ID, '_installation_start_date', true),
        )
    );

    // Add End Date field with datepicker
    woocommerce_wp_text_input(
        array(
            'id' => '_installation_end_date[' . $variation->ID . ']',
            'label' => __('Installment End Date', 'woocommerce'),
            'data_type' => 'date',
            'value' => get_post_meta($variation->ID, '_installation_end_date', true),
        )
    );
}

add_action('woocommerce_variation_options_pricing', 'custom_woocommerce_add_variation_installation_price_field', 10, 3);

// Save installation price for variable product variations
function custom_woocommerce_save_variation_installation_price($variation_id, $i) {
    $installation_price = isset($_POST['_installation_price'][$variation_id]) ? sanitize_text_field($_POST['_installation_price'][$variation_id]) : '';
    $start_date = isset($_POST['_installation_start_date'][$variation_id]) ? sanitize_text_field($_POST['_installation_start_date'][$variation_id]) : '';
    $end_date = isset($_POST['_installation_end_date'][$variation_id]) ? sanitize_text_field($_POST['_installation_end_date'][$variation_id]) : '';

    update_post_meta($variation_id, '_installation_price', $installation_price);
    update_post_meta($variation_id, '_installation_start_date', $start_date);
    update_post_meta($variation_id, '_installation_end_date', $end_date);
}

add_action('woocommerce_save_product_variation', 'custom_woocommerce_save_variation_installation_price', 10, 2);

//!===========================================INSTALLMENT FIELD ON POST========================================

//*==============================================DISPLAY ON FRONTEND===========================================

//*==================================INSTALLMENT PRICE LABEL===============================

function installment_label_styling() {
    wp_enqueue_style('woocommerce-style', get_template_directory_uri() . '/woocommerce.css');
    $inline_css = "
        .installation-price{
            color: #0053a0;
            font-size: 12px;
            font-family: poppins;
        }
        @media screen and (max-width: 425px){
        .installation-price{
            color: #0053a0;
            font-size: 10px;
            font-family: poppins;
        }
        }
        @media screen and (max-width: 857px){
        .installation-price{
            color: #0053a0;
            font-size: 10px;
            font-family: poppins;
        }
        }
        .installation-price strong, .installation-price bdi{
            display: inline-block;
        }
        
        .monthly-options-list-wrap, .variations-monthly-details{
            cursor:pointer;
            user-select: none;
            width:200px;
        }

        .monthly-options-list-header{
            background: #2b82c9;
            color: white;
            font-size: 16px;
            font-weight: 500 !important;
            padding: 5px;
            border: solid 1px black;
            border-radius: 10px;
        }
        .monthly-options-list-header:hover{
            background:#9aa4b5;
            color:#0053a0;
        }
        .monthly-options-list-wrap[open] .monthly-options-list-header, .variations-monthly-details[open] .monthly-options-list-header{
            border-bottom-left-radius:0;
            border-bottom-right-radius:0;
            background:#9aa4b5;
            color:#0053a0;          
        }
        .monthly-options-list-contents, .variations-monthly-details-contents{
            position:absolute;
            background:#dcdddd;
            z-index: 3;
            width:200px;
            border-bottom:solid 1px black;
            border-left:solid 1px black;
            border-right:solid 1px black;
            cursor: default;
            border-bottom-left-radius:10px;
            border-bottom-right-radius:10px;
        }
        .monthly-options-list-wrap td, .variations-monthly-details td{
            padding: 0px;
            font-size: 14px;
            padding-left: 2px;
            padding-right: 2px;
        }
        .monthly-options-list-wrap tr, .variations-monthly-details tr{
            border-bottom: solid 1px #0072ce;
            box-sizing: border-box;
            font-weight: 600 !important;
        }
            ";
wp_add_inline_style('woocommerce-style', $inline_css);
}
add_action('wp_enqueue_scripts', 'installment_label_styling');

// Add a custom accordion button to display monthly payments
function custom_woocommerce_product_accordion() {
    global $product;

    $installation_prices = array();

    if ($product->is_type('variable')) {
        $variation_ids = $product->get_children();

        foreach ($variation_ids as $variation_id) {
            $price = get_post_meta($variation_id, '_installation_price', true);

            if ($price) {
                $installation_prices[] = $price;
            }
        }
        if (!empty($installation_prices)) {
            $min_installation_price = min($installation_prices);
            echo '<div class="installation-price">';
            echo '<strong>';
            //! 'starts ' should be removed if all prices are equal
            //https://github.com/AV1DM1S/Installment-Price-WooCommerce/commit/542526f2710ea15c81bc12e3d17317b8c48fbab7
            //https://github.com/AV1DM1S/Installment-Price-WooCommerce/commit/e5318bc348a61dbaff27f61fbc7523b19a1d267d
            //*should only appear on the cheapest installation price  
            echo 'Installment price';
            //echo 'Affordable Monthly Payments</strong>';
            if ($price !== min($installation_prices)){
                echo ' starts at';}
            echo ': ' . wc_price($min_installation_price) . '</strong></div>';
        }
        else {
            echo '<div class="installation-price-empty"></div>';
        }
    } else {
        $price = get_post_meta($product->get_id(), '_installation_price', true);

        if ($price) {
            echo '<div class="installation-price"><strong>Installment price: ' . wc_price($price) . '</strong></div>';
            //echo '<div class="installation-price"><strong>Affordable Monthly Payments</strong></div>';
        }
        else {
            echo '<div class="installation-price-empty"></div>';
        }
    }

//!==================================INSTALLMENT PRICE LABEL===============================

//*========================================ACCORDION=======================================
  
    $monthly_prices = array();
    $product_id = $product->get_id();
    
    // Calculate monthly prices for the specified durations
    $month_splits = get_option('enable_months_offered', array());
    
    foreach ($month_splits as $duration) {
        $installation_price = get_post_meta($product_id, '_installation_price', true);
        if ($installation_price) {
            $monthly_price = $installation_price / $duration;
            $monthly_prices[$duration] = wc_price($monthly_price);
        }
    }

    if (!empty($monthly_prices && !is_category())) {
        if (is_product()){
        echo '<details class="monthly-options-list-wrap"><summary class="monthly-options-list-header"><center>Monthly Options</center></summary><div class="monthly-options-list-contents"><table>';
        foreach ($month_splits as $month){
            echo '<tr><td class="list-month-value">' . $month . ' months: </td><td class="list-price-value">' . wc_price($installation_price/$month) . '</td></tr>';
        }
//*=======================MONTHS THRESHOLD FOR SIMPLE PRODUCTS================================
/*
        if ($installation_price>=24000){
            foreach ($month_splits as $month){
                if ($month <=24){
                    echo '<li>' . $month . ' months: ' . wc_price($installation_price/$month) . '</li>';
                }
            }    
        }
        else if ($installation_price>=10000){
            foreach ($month_splits as $month){
                if ($month <=12){
                    echo '<li>' . $month . ' months: ' . wc_price($installation_price/$month) . '</li>';
                }
            }    
        }
        else if ($installation_price>=3000){
            foreach ($month_splits as $month){
                if ($month <=3){
                    echo '<li>' . $month . ' months: ' . wc_price($installation_price/$month) . '</li>';
                }
            }    
        }
*/
//!=======================MONTHS THRESHOLD FOR SIMPLE PRODUCTS================================

        echo '</table><img src="https://www.gtcapital.com.ph/storage/uploads/2017/09/59bc94ce59565.png" alt="Supported Bank" width="250" height="300"></div>';
        }
        echo '</details>';
    }
    // Display variations' monthly prices
    if ($product->is_type('variable')) {
        $variations = $product->get_available_variations();

        foreach ($variations as $variation) {
            $variation_id = $variation['variation_id'];
            $variation_attributes = $variation['attributes'];
            $variation_title = implode('/', $variation_attributes);

            $monthly_variation_prices = array();

            foreach ($month_splits as $duration) {
                $installation_price = get_post_meta($variation_id, '_installation_price', true);
                if ($installation_price) {
                    $monthly_price = $installation_price / $duration;
                    $monthly_variation_prices[$duration] = wc_price($monthly_price);
                }
            }

            if (!empty($monthly_variation_prices)) {
                if (is_product()){
                echo '<details style="display: none;" class="variations-monthly-details monthly-options-list-wrap-' . $variation_id . '"><summary class="monthly-options-list-header">Monthly Options - ' . $variation_title . '</summary><div class="variations-monthly-details-contents"><table>'; // Hide content by default
                foreach ($month_splits as $month){
                    echo '<tr><td class="list-month-value">' . $month . ' months: </td><td class="list-price-value">' . wc_price($installation_price/$month) . '</td></tr>';
                }
            
//*=======================MONTHS THRESHOLD FOR VARIABLE PRODUCTS==============================
/*
            if ($installation_price>=24000){
                foreach ($month_splits as $month){
                    if ($month <=24){
                        echo '<p class="accordion-list">For ' . $month . ' months: ' . wc_price($installation_price/$month) . '</p>';
                    }
                }    
            }
            else if ($installation_price>=10000){
                foreach ($month_splits as $month){
                    if ($month <=12){
                        echo '<p class="accordion-list">For ' . $month . ' months: ' . wc_price($installation_price/$month) . '</p>';
                    }
                }    
            }
            else if ($installation_price>=3000){
                foreach ($month_splits as $month){
                    if ($month <=3){
                        echo '<p class="accordion-list">For ' . $month . ' months: ' . wc_price($installation_price/$month) . '</p>';
                    }
                }    
            }
*/
//!=======================MONTHS THRESHOLD FOR VARIABLE PRODUCTS==============================

                echo '</table><img src="https://www.gtcapital.com.ph/storage/uploads/2017/09/59bc94ce59565.png" alt="Supported Bank" width="250" height="300"></div>';
                echo '</details>';
            }
            }
        }
    }
}
add_action('woocommerce_after_shop_loop_item', 'custom_woocommerce_product_accordion');
add_action('woocommerce_after_add_to_cart_form', 'custom_woocommerce_product_accordion');

// Add JavaScript to toggle accordion content based on variation selection
function custom_woocommerce_product_accordion_script() {
    global $product;
    ?>
    <script>
    jQuery(document).ready(function($) {
            $('.variations_form').on('change', 'input[name="variation_id"]', function() {
                var variation_id = $(this).val();
                $('.variations-monthly-details').hide();
                $('.monthly-options-list-wrap-' + variation_id).show();
            });
        });
    </script>
    <?php
}

add_action('wp_footer', 'custom_woocommerce_product_accordion_script');

//!========================================ACCORDION=======================================

//!==============================================DISPLAY ON FRONTEND===========================================

//*=============================================HIDE 2C2P CONDITIONS===========================================

// hide 2c2p if ANY product in cart has null installment
function custom_woocommerce_hide_2c2p_payment_gateway($gateways){
    if (!is_admin()){
        $has_null_installation_price = false;

        foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item){
            //single
            if ($cart_item['data']->is_type('simple')) {
                $product_id = $cart_item['product_id'];
                $installation_price = get_post_meta($product_id, '_installation_price', true);
                if ($installation_price == '') {
                    $has_null_installation_price = true;
                    break;
                }
            }
            //variatiable
            if ($cart_item['data']->is_type('variation')) {
                $variation_id = $cart_item['variation_id'];
                $variation_installation_price = get_post_meta($variation_id, '_installation_price', true);
                if ($variation_installation_price == '') {
                    $has_null_installation_price = true;
                    break;
                }
            }
        }
        if ($has_null_installation_price && isset($gateways['2c2p'])) {
            unset($gateways['2c2p']);
            //echo 'hidden';
        }  
    }
    return $gateways;
}
add_filter('woocommerce_available_payment_gateways', 'custom_woocommerce_hide_2c2p_payment_gateway');

//!=============================================HIDE 2C2P CONDITIONS===========================================

//*=====================================CHANGE PRICE DISPLAYED ON CHECKOUT=====================================

// Add custom price for 2c2p payment gateway
function custom_woocommerce_add_custom_price( $cart_obj ) {
    if ( is_admin() && ! defined( 'DOING_AJAX' ) )
        return;
    if ('2c2p' === WC()->session->get('chosen_payment_method') ) {
        foreach ( $cart_obj->get_cart() as $cart_item ) {
            //simple
            if ( $cart_item['data']->is_type( 'simple' ) ) {
                $product_id = $cart_item['product_id'];
                $fee = get_post_meta( $product_id, '_installation_price', true );
                $cart_item['data']->set_price( $fee );
            }
            //variable
            if ( $cart_item['data']->is_type( 'variation' ) ) {
                $variation_id = $cart_item['variation_id'];
                $fee = get_post_meta( $variation_id, '_installation_price', true );
                $cart_item['data']->set_price( $fee );
            }
        }
    }
}
add_action( 'woocommerce_before_calculate_totals', 'custom_woocommerce_add_custom_price', 10, 1);

function custom_checkout_jqscript() {
    if ( is_checkout() && ! is_wc_endpoint_url() ) :
    ?>
    <script type="text/javascript">
    jQuery( function($) {
        window.addEventListener('beforeunload', function() {
            $('input[name="payment_method"]').first().prop('checked', true).change();
            $();
        });

        $('form.checkout').on('change', 'input[name="payment_method"]', function() {
            $(document.body).trigger('update_checkout');
        });
    });
    </script>
    <?php
    endif;
}
add_action( 'wp_footer', 'custom_checkout_jqscript' );

//!=====================================CHANGE PRICE DISPLAYED ON CHECKOUT=====================================

//*============================UNAPPLY INSTALLMENT PRICES AND TOTALS ON CART PAGE==============================
// Update cart item prices on cart page
function custom_woocommerce_update_cart_item_prices($cart) {
    if (is_cart()) {
        foreach ($cart->get_cart() as $cart_item_key => $cart_item) {
            $product = $cart_item['data'];
            //simple
            if ($product && $product->is_type('simple')) {
                $product_id = $product->get_id();
                
                $sale_price = $product->get_sale_price();
                $regular_price = $product->get_regular_price();

                $effective_price = !empty($sale_price) ? $sale_price : $regular_price;

                $product->set_price($effective_price);
            }
            //variation
            if ($product && $product->is_type('variation')) {
                $variation_id = $product->get_id();

                $sale_price = $product->get_sale_price();
                $regular_price = $product->get_regular_price();

                $effective_price = !empty($sale_price) ? $sale_price : $regular_price;

                $product->set_price($effective_price);
            }
        }
    }
}
add_action('woocommerce_before_calculate_totals', 'custom_woocommerce_update_cart_item_prices', 10, 1);

//equal total cart to cart header
function custom_woocommerce_update_cart_total_in_header() {
    if (is_cart()) {
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function($) {
                function updateHeaderCartTotal() {
                    var cartTotalOnCartPage = $('.cart-subtotal .woocommerce-Price-amount').html();
                    $('.header-cart-total').html(cartTotalOnCartPage);
                }

                updateHeaderCartTotal();

                $(document.body).on('updated_cart_totals', function() {
                    updateHeaderCartTotal();
                });
            });
        </script>
        <?php
    }
}
add_action('wp_footer', 'custom_woocommerce_update_cart_total_in_header');

//!============================UNAPPLY INSTALLMENT PRICES AND TOTALS ON CART PAGE==============================

//? adjust sale installment by percentage or by regular and sale differencee
//todo show 24 months for !minimum variations (done)
//todo hide on variations ()
//todo remove 'starts' in installment24 months label IF all variations are equal (done)



//class-wc-meta-box-product-data.php
//html-product-data-general.php
//html-variation-admin

function them_available_gateways() {
    // Get all enabled payment gateways
    echo '<strong>' . date("Y-d-m") . '</strong><br>';
    $enabled_gateways = WC()->payment_gateways->get_available_payment_gateways();

    // Check if there are any enabled gateways
    if ($enabled_gateways) {
        // Loop through each enabled gateway and echo the ID or slug
        foreach ($enabled_gateways as $gateway) {
            echo $gateway->id . '<br>'; // Use $gateway->id for ID or $gateway->slug for slug
        }
    } else {
        echo 'No payment gateways are available.';
    }
}
add_action('wp_footer', 'them_available_gateways');
