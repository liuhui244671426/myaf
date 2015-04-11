<?php

class HaloXhprof
{
    private static function isOpenXhpro()
    {
        $config = Yaf_Registry::get('config');
        return $config->xhprof->enable;
    }

    public static function enable()
    {
        if ($_SERVER['REQUEST_URI'] == '/feed/heart') {
            return;
        }
        if (HaloXhprof::isOpenXhpro()) {
            xhprof_enable(XHPROF_FLAGS_CPU + XHPROF_FLAGS_MEMORY);
        }
    }

    public static function disable($target = true)
    {
        if ($_SERVER['REQUEST_URI'] == '/feed/heart') {
            return;
        }
        if (HaloXhprof::isOpenXhpro()) {
            $xhprofData = xhprof_disable();

            Yaf_Loader::import(sprintf('%s/xhprof/xhprof_lib.php', LIB_PATH));
            Yaf_Loader::import(sprintf('%s/xhprof/xhprof_runs.php', LIB_PATH));

            $xhprofRuns = new XHProfRuns_Default();
            $run_id = $xhprofRuns->save_run($xhprofData, "xhprof");
            if ($target) {
                $config = Yaf_Registry::get('config');
                echo '<a href="http://' . $config->xhprof->host . '/index.php?run=' . $run_id . '&source=xhprof" target="_blank">统计信息</a>';
            }
        }
    }
}

?>