<?php
/*
Plugin Name: Error Handling With Stack Dump
Plugin URI: http://codementor.io/davidbrumbaugh
Description: Replaces the standard error hanlder with one that generates a stack dump
Version: 0.1.0
Author: David Brumbaugh
Author URI: http://codementor.io/davidbrumbaugh
*/

if ( WP_DEBUG ) {
	if ( ! function_exists( 'StackDump_ErrorHandler' ) ) {
		// set to the user defined error handler
		$old_error_handler = set_error_handler( 'StackDump_ErrorHandler' );

		// error handler function
		function StackDump_ErrorHandler( $errno, $errstr, $errfile, $errline )
		{
			if ( ! ( error_reporting() & $errno) ) {
				// This error code is not included in error_reporting
				return;
			}

			switch ($errno) {
				case E_USER_ERROR:
				echo "<b>My ERROR</b> [$errno] $errstr<br />\n";
				echo "	Fatal error on line $errline in file $errfile";
				echo ", PHP " . PHP_VERSION . " (" . PHP_OS . ")<br />\n";
				echo "Aborting...<br />\n";
				var_dump(debug_backtrace());
				exit(1);
				break;

				case E_USER_WARNING:
				echo "<b>My WARNING</b> [$errno] $errstr<br />\n";
				var_dump(debug_backtrace());
				break;

				case E_USER_NOTICE:
				echo "<b>My NOTICE</b> [$errno] $errstr<br />\n";
				var_dump(debug_backtrace());
				break;

				default:
				echo "Unknown error type: [$errno] $errstr<br />\n";
				var_dump(debug_backtrace());
				break;
			}

			/* Don't execute PHP internal error handler */
			return true;
		}
	}
}
