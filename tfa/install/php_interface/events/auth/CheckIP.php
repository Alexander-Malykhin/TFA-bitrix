<?php

namespace Local\Events\Auth;

use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use Bitrix\Main\UserTable;
use Bitrix\Security\Mfa\Otp;

class CheckIP
{
    protected int $userId;
    protected string $ip;

    public static function onBeforeUserLogin(&$arFields)
    {
        if (!Loader::includeModule('security')) {
            return;
        }

        $userId = self::getUserIdByLogin($arFields['LOGIN']);
        if ($userId <= 0) {
            return;
        }

        $ip = self::getClientIp();
        $otp = Otp::getByUser($userId);
        if (!$otp) {
            return;
        }

        if (self::isInternalIp($ip) && $otp->canSkipMandatory()) {
            $_SESSION['SKIP_OTP_CHECK'] = true;
            $otp->deactivate(0);
        } else {
            unset($_SESSION['SKIP_OTP_CHECK']);
        }
    }

    public static function onAfterUserAuthorize($arParams)
    {
        if (!empty($_SESSION['SKIP_OTP_CHECK'])) {
            $userId = $arParams['user_fields']['ID'];

            $otp = Otp::getByUser($userId);
            $otp->activate();

            LocalRedirect('/');
        }
    }

    protected static function getUserIdByLogin(string $login): int
    {
        $user = UserTable::getList([
            'filter' => ['=LOGIN' => $login],
            'select' => ['ID'],
            'limit' => 1,
        ])->fetch();

        return $user ? (int)$user['ID'] : 0;
    }

    protected static function getClientIp(): string
    {
        return Application::getInstance()->getContext()->getRequest()->getRemoteAddress();
    }


    protected static function isInternalIp(string $ip): bool
    {
        return preg_match('/^10\./', $ip);
    }
}



