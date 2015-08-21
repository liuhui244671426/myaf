<?php

class HaloSync
{
    public static function sync($data)
    {
        $config = HaloEnv::get('config');
        //if($config->debug== 1)
        //    return true;

        $service = new HaloService($config['sync']['host'], $config['sync']['port']);
        return $service->postText($data, '/sync');
    }
}
