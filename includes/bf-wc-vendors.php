<?php

remove_action( 'woocommerce_get_item_data', array( 'WCV_Vendor_Cart', 'sold_by' ), 10, 2 );
add_action( 'woocommerce_get_item_data', 'tk_sold_by', 10, 2);
function tk_sold_by( $values, $cart_item ){

    $author_id = $cart_item[ 'data' ]->post->post_author;
    $sold_by =  bp_core_get_userlink( $author_id, false, true );
    $sold_by = '<a href="'. $sold_by .'/products">'.bp_core_get_user_displayname( $author_id ).'</a>';

    $values[ ] = array(
        'name'    => apply_filters('wcvendors_cart_sold_by', __( 'Sold by', 'wcvendors' )),
        'display' => $sold_by,
    );

    return $values;
}

remove_action( 'woocommerce_product_meta_start', array( 'WCV_Vendor_Cart', 'sold_by_meta' ), 10, 2 );
add_action( 'woocommerce_product_meta_start', 'tk_sold_by_meta', 10, 2);
function tk_sold_by_meta()
{
    $author_id = get_the_author_meta( 'ID' );
    $sold_by =  bp_core_get_userlink( $author_id, false, true );


    $sold_by = WCV_Vendors::is_vendor( $author_id )
        ? sprintf( '<a href="%sproducts">%s</a>', $sold_by, bp_core_get_user_displayname( $author_id ).'' )
        : get_bloginfo( 'name' );

    echo apply_filters('wcvendors_cart_sold_by_meta', __( 'Sold by: ', 'wcvendors' )) . $sold_by . '<br/>';
}

remove_action( 'woocommerce_after_shop_loop_item', array('WCV_Vendor_Shop', 'template_loop_sold_by'), 9, 2);
add_action( 'woocommerce_after_shop_loop_item', 'tk_template_loop_sold_by', 9, 2);
function tk_template_loop_sold_by($product_id) {
    $author     = WCV_Vendors::get_vendor_from_product( $product_id );
    $sold_by =  bp_core_get_userlink( $author, false, true );
    echo '<small>' . apply_filters('wcvendors_sold_by_in_loop', __( 'Solds by: ', 'wcvendors' )).'<a href="'. $sold_by .'/products">'.bp_core_get_user_displayname( $author ).'</a></small> <br />';
}