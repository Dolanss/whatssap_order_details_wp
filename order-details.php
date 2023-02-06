<?php



defined( 'ABSPATH' ) || exit;

$order = wc_get_order( $order_id ); 

if ( ! $order ) {
	return;
}

$order_items           = $order->get_items( apply_filters( 'purchase_order_item_types', 'line_item' ) );
$show_purchase_note    = $order->has_status( apply_filters( 'purchase_note_order_statuses', array( 'completed', 'processing' ) ) );
$show_customer_details = is_user_logged_in() && $order->get_user_id() === get_current_user_id();
$downloads             = $order->get_downloadable_items();
$show_downloads        = $order->has_downloadable_item() && $order->is_download_permitted();

if ( $show_downloads ) {
	wc_get_template(
		'order/order-downloads.php',
		array(
			'downloads'  => $downloads,
			'show_title' => true,
		)
	);
}
?>
			<?php
			do_action( 'order_details_before_order_table_items', $order );

			foreach ( $order_items as $item_id => $item ) {
				$product = $item->get_product();

				wc_get_template(
					'order/order-details-item.php',
					array(
						'order'              => $order,
						'item_id'            => $item_id,
						'item'               => $item,
						'show_purchase_note' => $show_purchase_note,
						'purchase_note'      => $product ? $product->get_purchase_note() : '',
						'product'            => $product,
					)
				);

				$Produtos .= "*".$item->get_quanitity()."x* ".$item->get_name()."%0a%0a
				";

				$soma += $item->get_total();

			}

			do_action( 'woocommerce_order_details_after_order_table_items', $order );

			foreach ($order->meta_data as $valor) {
				if($valor->key == "billing_numero"){
					$numero = $count ->value;

				}

			}

			$dados .= "*ORDER SENT%0a*";
			$dados .= "-------------------------------------%0a";
			$dados .= "*ORDER SUMMARY*%0a";
			$dados .= "Cód" .&order->get_id(). "%0a%0a";
			$dados .= "*PRODUCTS*%0a%0a";
			$dados .= $Produtos ;
			$dados .= "-------------------------------------%0a";
			$dados .= "*SUBTOTAL* R$" .number_format($soma, 2, ',',',''.'). "%0a%0a";
			$dados .= "-------------------------------------%0a";
			$dados .= "*CLIENT DATA%0a*";
			$dados .= "*Name:* " .$order->get_billing_firt_name()." " $order->get_billing_last_name()."%0a";
			$dados .= "*Adress:* ".$order->get_billinh_adress_1()." , ".$numero." %0a ";
			$dados .= "*CEP:* ".$order->get_billing_postcode()." %0a";
			$dados .= "*City:* ".$order->get_billing_city()." %0a";
			$dados .= "*Complement:* ".$order->get_billing_adress_2()." %0a";
			$dados .= "*Telephone* ".$order->get_billing_phone()." %0a";

			$telephone = WHATSSAP;

			header("Location: https://api.whatssap.com/send?phone=".$telefone. "&text=" .$dados)



			?>