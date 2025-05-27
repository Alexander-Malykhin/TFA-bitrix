<?php
use Bitrix\Main\EventManager;
use Bitrix\Main\Loader;

Loader::registerAutoLoadClasses(null, [
    'Local\Events\Auth\CheckIP' => '/local/php_interface/events/auth/CheckIP.php',
]);

$em = EventManager::getInstance();

$em->addEventHandlerCompatible(
    'main',
    'OnBeforeUserLogin',
    ['Local\Events\Auth\CheckIP', 'onBeforeUserLogin']
);

$em->addEventHandlerCompatible(
    'main',
    'OnAfterUserAuthorize',
    ['Local\Events\Auth\CheckIP', 'onAfterUserAuthorize']
);

//$this->ip = '10.0.15.23';
//return '10.0.15.23';