//Date function of PHP
<?php echo date('d F Y',strtotime($event_date)); ?>
//Output - 6 April 2019 


//Multiple table delete 
<?php

	require_once('../inc/header.php'); 

	if(isset($_GET['id'])){

		$id = $_GET['id'];

		$delete_shipment = mysqli_query($conn, "DELETE bill_to, booking_number, parcel_info, receiver_location,  	sender_location, services_payment_info, shipment_date_time, shipment_info, shipment_type FROM bill_to, booking_number, parcel_info, receiver_location, sender_location, services_payment_info, shipment_date_time, shipment_info, shipment_type WHERE booking_number.bid = '$id' AND booking_number.bid = bill_to.bid AND booking_number.bid = parcel_info.bid AND  booking_number.bid = receiver_location.bid AND booking_number.bid = sender_location.bid AND booking_number.bid = services_payment_info.bid AND booking_number.bid = shipment_date_time.bid AND booking_number.bid = shipment_info.bid AND booking_number.bid = shipment_type.bid ") or die(mysqli_error($conn));

		if($delete_shipment){

			header('Location: undelivered_shipment.php');
		}
	}

?>
