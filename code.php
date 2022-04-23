<?php

/*
Fires when a successful payment is made. This hook is used to show the correct next renewal date on Thank You page.
It allows companies to prevent customers from being charged twice if the renewal date is set to be after the current date
*/

add_action('woocommerce_payment_complete', 'nextpaymentdatechange');
function nextpaymentdatechange( $order_id ){
	$user_id= get_current_user_id();
    if ( wcs_order_contains_subscription( $order_id ) ) {
			$order = wc_get_order( $order_id );
			$items = $order->get_items(); 
			foreach ( $items as $item_id => $item ) {
   			$product_id = $item->get_variation_id() ? $item->get_variation_id() : $item->get_product_id();
      		if ( $product_id == 317 ) {
				
				$today = date('Y-d-m');
				$todayStamp = time();
				$numOfDays = date('t', $todayStamp);
				$base = strtotime('+'.$numOfDays.' days', strtotime(date('Y-m-01', $todayStamp)));
				$day15 = date('Y-m-15 H:i:s', $base);
				$subid = $order_id + 1;
				$nextdate = get_post_meta( $subid, '_schedule_next_payment', true );
				$next_renewal_updated = date( 'Y-m-d H:i:s', strtotime( $day15, strtotime( $nextdate )) );
				update_post_meta( $subid , '_schedule_next_payment', $next_renewal_updated);
     		}
			else {
				
				$today = date("d");
				$subid = $order_id + 1;
				$nextdate = get_post_meta( $subid, '_schedule_next_payment', true );
				$nextdate_day = date('j', strtotime($nextdate));
				if($today < $nextdate_day) {
					$next_renewal_updated = date( 'Y-m-d H:i:s', strtotime('+1 month', strtotime( $nextdate )) );
					update_post_meta( $subid , '_schedule_next_payment', $next_renewal_updated);
				}
				else {
					$next_renewal_updated = date( 'Y-m-d H:i:s', strtotime( $nextdate ));
					update_post_meta( $subid , '_schedule_next_payment', $next_renewal_updated );
				}

			}
		}
	}
}
