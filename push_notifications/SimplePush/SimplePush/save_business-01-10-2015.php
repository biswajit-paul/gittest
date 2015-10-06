<?php
//print_r($_POST);

define('WP_USE_THEMES', false);
require( '../../../../../wp-load.php');
include_once ABSPATH . '/webservices/GCM.php';
global $wpdb;

	// Verifying Business information & performing update to database
	if( isset($_POST['business_dashboard_nonce']) )
	{
		if( wp_verify_nonce( $_POST['business_dashboard_nonce'], 'business_dashboard' ) )
		{
			$profilepost = array();
			$profilepostmeta = array();
			$postid = $_POST['post_id'];

			//echo '<pre>'; print_r($_POST); print_r($_FILES); echo '</pre>';
/*	
			if(isset($_POST['updateCoupon']))
			{
				if(($_FILES['updateCoupon']['error']) == 0)
				{
					$tmp = $_FILES['updateCoupon']['tmp_name'];
					$desc = "Coupon Logo";
					$file_array = array();	
					
					// Set variables for storage
					// fix file filename for query strings
					$matched_type = preg_match('/[^\?]+\.(jpg|jpe|jpeg|gif|png)/i', $_FILES['updateCoupon']['name'], $matches);
					if($matched_type)
					{
						$file_array['name'] = basename($matches[0]);
						$file_array['tmp_name'] = $tmp;
			
						// If error storing temporarily, unlink
						if ( is_wp_error( $tmp ) ) {
							@unlink($file_array['tmp_name']);
							$file_array['tmp_name'] = '';
							echo json_encode( array( 'type' => 'error', 'message' => 'Something went wrong while uploading image. Please try again.' ) );
						}
						
						$attach_id = media_handle_sideload( $file_array, null, $desc );
						add_post_meta( $postid, '_wpbdp[fields][26]', "$attach_id", true ) || update_post_meta( $postid, '_wpbdp[fields][26]', "$attach_id" );	
			
						// If error storing permanently, unlink
						if ( is_wp_error($attach_id) ) {
							@unlink($file_array['tmp_name']);
							echo json_encode( array( 'type' => 'error', 'message' => 'Something went wrong while uploading image. Please try again.' ) );
							return $attach_id;
						}								
					}	
					else
					{
						echo json_encode( array( 'type' => 'error', 'message' => 'Invalid image type! Only jpg / png / gif type supported.' ) );
					}
				}
			}
*/						

			$profilepost['ID'] = $postid;
			$profilepost['post_title'] 			   = sanitize_text_field($_POST['businessName']);
			$profilepostmeta['_wpbdp[fields][8]']  = sanitize_text_field($_POST['businessEmail']);
			$profilepostmeta['_wpbdp[fields][10]'] = sanitize_text_field($_POST['businessAddress']);
			$profilepostmeta['_wpbdp[fields][12]'] = sanitize_text_field($_POST['businessCity']);
			$profilepostmeta['_wpbdp[fields][13]'] = sanitize_text_field($_POST['businessState']);
			$profilepostmeta['_wpbdp[fields][14]'] = sanitize_text_field($_POST['businessZip']);
			$profilepostmeta['_wpbdp[fields][6]']  = sanitize_text_field($_POST['businessPhone']);
			$profilepostmeta['_wpbdp[fields][5]']  = esc_url($_POST['businessWebsite']);
			$profilepostmeta['_wpbdp[fields][17]'] = sanitize_text_field($_POST['businessVideo1']);
			$profilepostmeta['_wpbdp[fields][18]'] = sanitize_text_field($_POST['businessVideo2']);
			$profilepostmeta['_wpbdp[fields][19]'] = sanitize_text_field($_POST['businessVideo3']);
			$profilepostmeta['_wpbdp[fields][20]'] = sanitize_text_field($_POST['businessVideo4']);
			$profilepostmeta['_wpbdp[fields][21]'] = sanitize_text_field($_POST['businessVideo5']);
			$profilepostmeta['_wpbdp[fields][22]'] = esc_textarea($_POST['businessService']);
			$profilepostmeta['_wpbdp[fields][23]'] = esc_textarea($_POST['businessLocation']);
			$profilepostmeta['_wpbdp[fields][24]'] = $_POST['hours'];
			$profilepostmeta['_wpbdp[fields][15]'] = sanitize_text_field($_POST['businessLatitude']);
			$profilepostmeta['_wpbdp[fields][16]'] = sanitize_text_field($_POST['businessLongitude']);
      if( isset( $_POST['coupon_enabled'] ) ):
        $coupon_enabled = $profilepostmeta['coupon_enabled'] = filter_var($_POST['coupon_enabled'], FILTER_VALIDATE_INT);
      else:
        $coupon_enabled = $profilepostmeta['coupon_enabled'] = 0;
      endif;
			$profilepostmeta['coupon_code']				 = sanitize_text_field($_POST['coupon_code']);
			$profilepostmeta['coupon_start_date']	 = sanitize_text_field($_POST['coupon_start_date']);
			$profilepostmeta['coupon_end_date']		 = sanitize_text_field($_POST['coupon_end_date']); 
			$profilepostmeta['coupon_url']			= esc_url( $_POST['coupon_url'] );
			$profilepostmeta['coupon_desc']		 		 = esc_textarea($_POST['coupon_desc']);
			$profilepostmeta['_wpbdp[fields][28]'] = (isset($_POST['adsenseCode']))?$_POST['adsenseCode']:'';
			if($current_user->prfile_role == 'paid'):
				$profilepostmeta['_wpbdp[fields][27]'] = esc_textarea($_POST['businessAdsense']);
			endif;
			$profilepost['post_content'] = esc_textarea($_POST['businessAbout']);

			// Update the post into the database
			wp_update_post( $profilepost );
			$post = get_post($postid);

			// Before updating post meta check previous coupon information to send GCM Notification
			$coupon_biz_name = $post->post_title;
			$prev_coupon_code = get_post_meta($postid, 'coupon_code', true);
			$prev_coupon_start_date = get_post_meta($postid, 'coupon_start_date', true);
			$prev_coupon_end_date = get_post_meta($postid, 'coupon_end_date', true);
			$prev_coupon_desc = get_post_meta($postid, 'coupon_desc', true);

			//if( $_POST['coupon_code'] != $prev_coupon_code )
      if( $coupon_enabled )
			{
/*
				//die('one');
				require_once 'GCM.php';
				$gcm = new GCM();
				$registatoin_ids = array();

				$args = array(
					'meta_query' => array(
							array(
									'key' => 'business_id',
									'value' => $postid
							)
					),
					'post_type' => 'biz_subscription',
					'post_status' => 'publish',
					'posts_per_page' => -1
				);
				$query = new WP_Query($args);
			
				if( $query->have_posts() )
				{
					while( $query->have_posts() )
					{
						$query->the_post();
						$registatoin_ids[] = get_post_meta( get_the_ID(), 'registration_id', true );			
					}
				}

				wp_reset_query();
				
				$msg_body = $coupon_biz_name . ': Use coupon code ' . $_POST['coupon_code'] . ' to avail ' . $_POST['coupon_desc'] . ' on ' .  $_POST['coupon_start_date'] . ' to  ' . $_POST['coupon_end_date'];		

				$message = array( 'message' => $msg_body ); 
		
				$result = $gcm->send_notification($registatoin_ids, $message);
*/
        $passphrase = 'b3net';
        $android_devices = $ios_devices = array();
        
        $wpdb->query('SET SESSION group_concat_max_len = 10000'); // necessary to get more than 1024 characters in the GROUP_CONCAT columns below
        $query = "
            SELECT p.ID, 
            GROUP_CONCAT(pm.meta_key ORDER BY pm.meta_key DESC SEPARATOR '||') as meta_keys, 
            GROUP_CONCAT(pm.meta_value ORDER BY pm.meta_key DESC SEPARATOR '||') as meta_values 
            FROM $wpdb->posts p 
            LEFT JOIN $wpdb->postmeta pm on pm.post_id = p.ID 
            WHERE p.post_type = 'biz_subscription' and p.post_status = 'publish'
            GROUP BY p.ID
        ";
        
        $businesses = $wpdb->get_results($query);
      
        function massage($a){
            $a->meta = array_combine(explode('||',$a->meta_keys),explode('||',$a->meta_values));
            unset($a->meta_keys);
            unset($a->meta_values);
            //echo '<pre>'; print_r($a); echo '</pre>';
            return $a;
        }
        
        $businesses = array_map('massage',$businesses);

        // Get all devices registration IDs
        foreach( $businesses as $business )
        {
          if( $business->meta['os_type'] == 'android' && $business->meta['business_id'] == $postid )
          {
            $android_devices[] = $business->meta['registration_id'];
          }
          elseif( $business->meta['os_type'] == 'ios' && $business->meta['business_id'] == $postid )
          {
            $ios_devices[] = $business->meta['registration_id'];
          }
        }

        // Send Push notification to Android devices
        if( count( $android_devices ) > 0 )
        {
          $gcm = new GCM();
          $msg_body = $coupon_biz_name . ': Use coupon code ' . $_POST['coupon_code'] . ' to avail ' . $_POST['coupon_desc'] . ' on ' .  $_POST['coupon_start_date'] . ' to  ' . $_POST['coupon_end_date'];		
          $message = array( 'message' => $msg_body ); 
          $result = $gcm->send_notification($android_devices, $message);
        }

        // Send Push notification to iOS
        if( count( $ios_devices ) > 0 )
        {
          $message = $coupon_biz_name . ': Use coupon code ' . $_POST['coupon_code'] . ' to avail ' . $_POST['coupon_desc'] . ' on ' .  $_POST['coupon_start_date'] . ' to  ' . $_POST['coupon_end_date'];		

          $ctx = stream_context_create();
          stream_context_set_option($ctx, 'ssl', 'local_cert', 'Vintelli.pem');
          stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);
          stream_context_set_option($ctx, 'ssl', 'cafile', 'entrust_2048_ca.cer');
          
          // Open a connection to the APNS server
          // ssl://gateway.sandbox.push.apple.com:2195
          // ssl://gateway.push.apple.com:2195
          $fp = stream_socket_client(
            'ssl://gateway.sandbox.push.apple.com:2195', $err,
            $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
          
          if (!$fp)
            exit("Failed to connect: $err $errstr" . PHP_EOL);
          
          echo 'Connected to APNS' . PHP_EOL;
          
          // Create the payload body
          $body['aps'] = array(
              'alert' => $message,
              'sound' => 'default'
            );
          
          // Encode the payload as JSON
          $payload = json_encode($body);
      
          foreach( $ios_devices as $deviceToken )
          {
            // Build the binary notification
            $msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;
            
            // Send it to the server
            $result = fwrite($fp, $msg, strlen($msg));
          }
          
          // Close the connection to the server
          fclose($fp);   
        }
			}

			// Update post meta into the database
			foreach ( $profilepostmeta as $key => $value ) {
				//add_post_meta( $postid, $key, $value, true ) || update_post_meta( $postid, $key, $value );
				update_post_meta( $postid, $key, $value );
			}

			echo json_encode( array( 'type' => 'success', 'message' => 'Business Profile updated successfully.' ) );
		}
		else
		{
			echo json_encode( array( 'type' => 'error', 'message' => 'Something went wrong. Please try again later.' ) );
		}	
	}
	