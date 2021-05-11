<?php

if (!function_exists('auth_user')) {
    /**
     * @param bool $must
     * @return \App\Models\User
     * @throws \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException
     */
    function auth_user($must = true)
    {
        $user = \Auth::user();
        if ($must && (!$user || !($user instanceof \App\Models\User))) {
            throw new \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException();
        }

        return $user;
    }
}

if (!function_exists('auth_employee')) {
    /**
     * @param bool $must
     * @return \App\Models\User\Employee
     * @throws \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException
     */
    function auth_employee($must = true)
    {
        $user = auth_user($must);
        if ($must && !$user->employee) {
            throw new \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException();
        }

        return $user->employee;
    }
}

if (!function_exists('human_filesize')) {
    function human_filesize($bytes, $decimals = 2)
    {
        $size = array('B','KB','MB','GB','TB','PB','EB','ZB','YB');
        $factor = floor((strlen($bytes) - 1) / 3);
        return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$size[$factor];
    }
}

if (!function_exists('geo_ip')) {
    function geo_ip($ip, $withoutProxy = false, $withoutGeo = false)
    {
        $client = new \Rpc\GeoIPClient('localhost:7324', ['credentials' => null]);
        $request = new \Rpc\GeoIPRequest();
        $request->setIp($ip);
        if ($withoutProxy) {
            $request->setWithoutProxy($withoutProxy);
        }
        if ($withoutGeo) {
            $request->setWithoutGeo($withoutGeo);
        }

        /**
         * @var \Rpc\GeoIPResponse $reply
         */
        [$reply, $status] = $client->Get($request)->wait();
        if (empty($status) || $status->code !== 0 || $reply === null) {
            return null;
        }

        return $reply;
    }
}
