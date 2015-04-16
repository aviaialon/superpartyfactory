<?php /*$this->renderPartial('products::listing_table', array(
	'products' 	=> $this->getViewData('products'),
	'apiUrl' 	=> $this->getViewData('apiUrl')
));*/ ?>

<?php
/*
	\Core\Debug\Dump::getInstance($session->get('test'));
	\Core\Debug\Dump::getInstance($session->get('whatever'));
	*/
	
	\Core\Session\Session::getInstance()->set('USER_ID', 138);
	//\Core\Debug\Dump::getInstance(\Core\Application::getInstance()->getConfigs()->get()); die;
	$u = \Core\Hybernate\Users\Users::getUser();
	var_dump($u->get());
	//$u->logout();
	echo 'Donw..';
	\Core\Debug\Dump::getInstance(\Core\Session\Session::getInstance()->toArray());
//	var_dump(get_class($session));