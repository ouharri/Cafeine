<?php

function mo_openid_account_linking_form( $username, $user_email, $first_name, $last_name, $user_full_name, $user_url, $user_picture, $decrypted_app_name, $decrypted_user_id ) {
	$path  = mo_openid_get_wp_style();
	$nonce = wp_create_nonce( 'mo-openid-account-linking-nonce' );
	$html  = '
                <style>
                    .mocomp {
                                 margin: auto !important;
                             }
                    @media only screen and (max-width: 600px) {
                      .mocomp {width: 90%;}
                    }
                    @media only screen and (min-width: 600px) {
                      .mocomp {width: 500px;}
                    }
                </style>
                <head>
				<meta name="viewport" content="width=device-width, initial-scale=1.0">
				<link rel="stylesheet" href=' . $path . " type='text/css' media='all' /><head>
                <body class='login login-action-login wp-core-ui  locale-en-us'>
                <div style=\"background:#f1f1f1;\"></div>
                <div id=\"add_field\" style=\"top: 0;right: 0;bottom: 0;left: 0;z-index: 1;padding-top:10%;\">
                <div class='mocomp'>
                <form name = 'f' method = 'post' action='' style='margin-left: 2%;margin-right: 2%;'>
                <input type = 'hidden' name = 'option' value = 'mo_openid_account_linking'/>
                <input type='hidden' name='mo_openid_account_linking_nonce' value='" . $nonce . "'/>
                <input type='hidden' name='user_email' value=" . esc_attr( $user_email ) . ">
                <input type='hidden' name='username' value=" . esc_attr( $username ) . ">
                <input type='hidden' name='first_name' value=" . esc_attr( $first_name ) . ">
                <input type='hidden' name='last_name' value=" . esc_attr( $last_name ) . ">
                <input type='hidden' name='user_full_name' value=" . esc_attr( $user_full_name ) . ">
                <input type='hidden' name='user_url' value=" . esc_url( $user_url ) . ">
                <input type='hidden' name='user_picture' value=" . esc_url( $user_picture ) . ">
                <input type='hidden' name='decrypted_app_name' value=" . esc_attr( $decrypted_app_name ) . ">
                <input type='hidden' name='decrypted_user_id' value=" . esc_attr( $decrypted_user_id ) . ">
                <div  style = 'background-color:white; padding:12px;top:100px; right: 350px; padding-bottom: 20px;left:350px; overflow:hidden; outline:1px black;border-radius: 5px;'>	
                <br>
                <div style=\"text-align:center\"><span style='font-size: 24px;font-family: Arial;text-align:center'>" . esc_html( get_option( 'mo_account_linking_title' ) ) . "</span></div><br>
                <div style='padding: 12px;'></div>
                <div style=' padding: 16px;background-color:rgba(1, 145, 191, 0.117647);color: black;'>" . get_option( 'mo_account_linking_new_user_instruction' ) . '.<br><br>' . get_option( 'mo_account_linking_existing_user_instruction' ) . '' . get_option( 'mo_account_linking_extra_instruction' ) . " 
                </div>                   
                <br><br>

                <input type = 'submit' value = '" . esc_attr( get_option( 'mo_account_linking_existing_user_button' ) ) . "' name = 'mo_openid_link_account' class='button button-primary button-large' style = 'margin-left: 3%;margin-right: 0%;'/>
                    
                <input type = 'submit' value = '" . esc_attr( get_option( 'mo_account_linking_new_user_button' ) ) . "' name = 'mo_openid_create_new_account' class='button button-primary button-large'style = 'margin-left: 5%margin-right: 5%;'/>";

	if ( get_option( 'moopenid_logo_check_account' ) == 1 ) {
		$html .= mo_openid_customize_logo();
	}

	$html .= '</div>
                    </form>
                    </div>
                    </div>
                    </body>';
	return $html;
}

function mo_openid_account_linking( $messages ) {
	if ( isset( $_GET['option'] ) && $_GET['option'] == 'disable-social-login_admin_verify' ) {
		update_option( 'account_linking_flow', 1 );
		$messages = '<p class="message">You are trying to login as admin. Please fill the details and continue login.</p>';
	}
	if ( isset( $_GET['option'] ) && $_GET['option'] == 'disable-social-login' ) {
		update_option( 'account_linking_flow', 1 );
		$messages = '<p class="message">' . get_option( 'mo_account_linking_message' ) . '</p>';
	}
	return $messages;
}

function mo_openid_social_linking_action() {
	if ( ! mo_openid_restrict_user() ) {
		$nonce = sanitize_text_field( $_POST['mo_openid_social_linking_nonce'] );
		if ( ! wp_verify_nonce( $nonce, 'mo-openid-social-linking' ) ) {
			wp_die( '<strong>ERROR</strong>: Please Go back and Refresh the page and try again!<br/>If you still face the same issue please contact your Administrator.' );
		} else {
			if ( current_user_can( 'administrator' ) ) {
				if ( sanitize_text_field( $_POST['enabled'] ) == 'true' ) {
					update_option( 'mo_openid_account_linking_enable', 1 );

				} else {
					update_option( 'mo_openid_account_linking_enable', 0 );
				}
			}
		}
	}
}
