<?php
namespace Xe;
class ErrorHandler {
	
	/**
	 * 异常处理
	 */
	public static function exec(\Exception $e) {
		try {
			// 日志异常
			if (method_exists ( $e, 'log' )) {
				$e->log ();
			} else {
				Logger::error ( $e->__toString () );
			}
			// Get the exception information
			$type = get_class ( $e );
			$code = $e->getCode ();
			$message = $e->getMessage ();
			$file = $e->getFile ();
			$line = $e->getLine ();
			// Create a text version of the exception
			if (PHP_SAPI === 'cli') {
				$error = Debug::exception_text ( $e );
				echo "\n{$error}\n"; // Just display the text of the exception
				return TRUE;
			}
			// Get the exception backtrace
			$trace = $e->getTrace ();
			if ($e instanceof ErrorException) {
				$php_errors = array (
						E_ERROR => 'Fatal Error',
						E_USER_ERROR => 'User Error',
						E_PARSE => 'Parse Error',
						E_WARNING => 'Warning',
						E_USER_WARNING => 'User Warning',
						E_STRICT => 'Strict',
						E_NOTICE => 'Notice',
						E_RECOVERABLE_ERROR => 'Recoverable Error' 
				);
				if (isset ( $php_errors [$code] )) {
					$code = $php_errors [$code]; // Use the human-readable error name
				}
				if (version_compare ( PHP_VERSION, '5.3', '<' )) {
					// Workaround for a bug in ErrorException::getTrace() that
					// exists in
					// all PHP 5.2 versions. @see
					// http://bugs.php.net/bug.php?id=45895
					for($i = count ( $trace ) - 1; $i > 0; -- $i) {
						if (isset ( $trace [$i - 1] ['args'] )) {
							// Re-position the args
							$trace [$i] ['args'] = $trace [$i - 1] ['args'];
							// Remove the args
							unset ( $trace [$i - 1] ['args'] );
						}
					}
				}
				$template = 'error';
			} else {
				$code = "Exception";
				$template = method_exists ( $e, 'get_template' ) ? $e->get_template () : 'error';
			}
			$message = $e->getMessage ();
			if (! headers_sent ()) {
				if (method_exists ( $e, 'send_headers' )) {
					$e->send_headers ();
				} else {
					// Make sure the proper content type is sent with a 500
					// status
					header ( 'Content-Type: text/html; charset=utf-8', TRUE, 500 );
					// header('HTTP/1.1 500 Internal Server Error');
				}
			}
			if (Util::isAjax ()) {
				if (ENVIRON !== 'production') {
					echo Debug::exception_text ( $e );
					exit ();
				} else {
					exit ( "\n{$message}\n" );
				}
			}
			// Include the exception HTML
			$flag = @include "views/{$template}.php";
			if (! $flag)
				echo Debug::exception_text ( $e ), "\n";
			return TRUE;
		} catch ( Exception $e ) {
			// Clean the output buffer if one exists
			ob_get_level () && ob_clean ();
			// Display the exception text
			echo Debug::exception_text ( $e ), "\n";
			// Exit with an error status
			// exit(1);
		}
	}
}