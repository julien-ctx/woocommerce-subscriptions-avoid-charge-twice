<?php

add_action( 'woocommerce_customer_changed_subscription_to_cancelled', 'customer_skip_pending_cancellation' );
/**
 * Change 'pending-cancel' status directly to 'cancelled'.
 *
 * @param WC_Subscription $subscription
 */
function customer_skip_pending_cancellation( $subscription ) {
	if ( 'pending-cancel' === $subscription->get_status() ) {
		$subscription->update_status( 'cancelled', 'Your subscription has been cancelled.' );
	}
}
?>
