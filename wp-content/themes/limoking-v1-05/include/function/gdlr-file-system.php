<?php
	/*	
	*	Goodlayers File System Management
	*	---------------------------------------------------------------------
	*	This file contains functions that help you read/write files
	*	---------------------------------------------------------------------
	*/
	
	// get remote file
	if( !function_exists('limoking_get_remote_file') ){
		function limoking_get_remote_file($url){
			$response = wp_remote_get($url);
			
			if( is_wp_error( $response ) ) {
				return array('success'=>false, 'error'=>$response->get_error_message());
			}else if( is_array($response) ) {
				return array('success'=>true, 'data'=>$response['body']);
			}
		}
	}

	// init file system
	if( !function_exists('limoking_init_filesystem') ){
		function limoking_init_filesystem($url){	
			if (false === ($creds = request_filesystem_credentials($url, '', false, false, null) ) ) {
				return false;
			}
			
			if (!WP_Filesystem($creds)){
				request_filesystem_credentials($url, '', true, false, null);
				return false;
			}
		}
	}	

	// read file -> replace the file_get_contents function
	// $current_page =  wp_nonce_url(admin_url('admin.php?import=goodlayers-importer'),'limoking-importer');
	if( !function_exists('limoking_read_filesystem') ){
		function limoking_read_filesystem($current_page, $url){	
			global $wp_filesystem;
			
			if( !empty($wp_filesystem) ){
				limoking_init_filesystem($current_page);
				return $wp_filesystem->get_contents($url);
			}
			return file_get_contents($url);
		}
	}		
	
	// write file -> replace the fwrite fopen fclose function
	// $current_page =  wp_nonce_url(admin_url('admin.php?import=goodlayers-importer'),'limoking-importer');
	if( !function_exists('limoking_write_filesystem') ){
		function limoking_write_filesystem($current_page, $url, $data){	
			global $wp_filesystem;
			
			if( !empty($wp_filesystem) ){
				limoking_init_filesystem($current_page);
				if (!$wp_filesystem->put_contents($url, $data, FS_CHMOD_FILE)){
					return false;
				}
			}else{
				$file_stream = @fopen($url, 'w');
				if( $file_stream ){
					fwrite($file_stream, $data);
					fclose($file_stream);
				}else{
					return false;
				}
			}
			
			return true;
		}
	}	