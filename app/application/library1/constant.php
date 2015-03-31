<?php
/**
 * Created by PhpStorm.
 * User: liuhui
 * Date: 15-1-15
 * Time: 下午10:38
 */
/**
 * APP
 * ==============================
 * */
//项目名
define('APP_NAME', '墨迹天气WebAPP项目');
//views目录
define('VIEW_PATH', APPLICATION_PATH . '/application/views/');
//缓存目录
define('CACHE_PATH', VIEW_PATH . 'cache/');
//phtmls目录
define('PHTML_PATH', VIEW_PATH . 'phtmls/');
//builders目录
define('BUILDER_PATH', VIEW_PATH . 'builders/');
//now time
define('TODAY', time());
//models
define('MODEL_PATH', APPLICATION_PATH . '/application/models/');
/**
 * APP
 * ==============================
 * */

//页码数量
define('PAGE_LIMIT', 10);
//AES key
define('AES_MJ_KEY', 'MojiWeathre');


/**
 * session
 * ==============================
 * */
//access
define('SES_ADM_IS_LOGIN', 'isLogin');
/**
 * session
 * ==============================
 **/

/**
 * ACL
 * ==============================
 */
//define('ACL_MODULE_NAME', array('admin'));
//define('ACL_CONTROLLER_NAME', array('articles', 'main', 'role'));
//define('ACL_ACTION_NAME', array('list', 'view', 'del', 'edit', 'insnew'));
/**
 * ACL
 * ==============================
 */