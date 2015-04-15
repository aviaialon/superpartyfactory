<?php /*$this->renderPartial('products::listing_table', array(
	'products' 	=> $this->getViewData('products'),
	'apiUrl' 	=> $this->getViewData('apiUrl')
));*/ ?>

<?php
/*
	$session = \Core\Session\Session::getInstance();
	\Core\Debug\Dump::getInstance($session->toArray());
	\Core\Debug\Dump::getInstance($session->get('test'));
	\Core\Debug\Dump::getInstance($session->get('whatever'));
	*/
	\Core\Session\Session::getInstance()->set('USER_ID', 138);
	$u = \Core\Hybernate\Users\Users::getUser();
	var_dump($u->get());
	$u->logout();
	echo 'Donw..';
//	var_dump(get_class($session));