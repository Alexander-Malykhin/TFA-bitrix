<?php

use Bitrix\Main\Loader;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\Config\Option;

class TFA extends CModule
{
    public $MODULE_ID = "tfa";
    public $MODULE_NAME;
    public $MODULE_DESCRIPTION;

    public function __construct()
    {
        include __DIR__ . "/version.php";
        $this->MODULE_NAME = "Двухфакторная авторизация";
        $this->MODULE_DESCRIPTION = "Модуль для двухфакторной авторизации";
    }

    //УСТАНОВКА МОДУЛЯ
    public function DoInstall(): true
    {
        $this->InstallFiles();
        ModuleManager::registerModule($this->MODULE_ID);
        return true;
    }

    //КОПИРОВАНИЕ ФАЙЛОВ
    public function InstallFiles(): void
    {
        $path = __DIR__;
        $documentRoot = $_SERVER["DOCUMENT_ROOT"];


        CopyDirFiles(
            $path . '/php_interface',
            $documentRoot . '/local/php_interface',
            true,
            true,
        );
    }

    //УДАЛЕНИЕ ФАЙЛОВ
    public function UnInstallFiles(): void
    {
        DeleteDirFilesEx("/local/php_interface");
    }

    //УДАЛЕНИЕ МОДУЛЯ
    public function DoUninstall(): true
    {
        $this->UnInstallFiles();
        ModuleManager::unRegisterModule($this->MODULE_ID);
        return true;
    }
}
