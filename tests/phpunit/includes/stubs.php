<?php

function __( $text, $domain ) {
	return $text;
}

function _doing_it_wrong( $function, $message ) {
	throw new Exception( "{$function} - {$message}" );
}
