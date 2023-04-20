<?php

namespace WP\Notifications;

interface Status_Interface {

	const NEW         = 'new';
	const UNDISPLAYED = 'undisplayed';
	const DISPLAYED   = 'displayed';
	const DISMISSED   = 'dismissed';

}
