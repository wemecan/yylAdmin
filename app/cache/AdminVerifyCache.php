<?php
/*
 * @Description  : 验证码缓存
 * @Author       : https://github.com/skyselang
 * @Date         : 2020-07-09
 */

namespace app\cache;

use think\facade\Cache;
use think\facade\Config;

class AdminVerifyCache
{
    /**
     * 验证码键
     *
     * @param string $verify_id 验证码id
     * @return string
     */
    public static function key($verify_id = '')
    {
        $key = 'adminVerify:' . $verify_id;

        return $key;
    }

    /**
     * 验证码有效时间
     *
     * @param integer $expire 有效时间
     * @return integer
     */
    public static function exp($expire = 0)
    {
        if (empty($expire)) {
            $expire = Config::get('captcha.expire', 180);
        }

        return $expire;
    }

    /**
     * 验证码设置
     *
     * @param integer $verify_id   验证码id
     * @param integer $verify_code 验证码
     * @param integer $expire      验证码有效时间
     * @return bool
     */
    public static function set($verify_id = 0, $verify_code = '', $expire = 0)
    {
        $key = self::key($verify_id);
        $val = $verify_code;
        $exp = $expire ?: self::exp();
        $res = Cache::set($key, $val, $exp);

        return $res;
    }

    /**
     * 验证码获取
     *
     * @param integer $verify_id 验证码id
     * @return string
     */
    public static function get($verify_id = 0)
    {
        $key = self::key($verify_id);
        $val = Cache::get($key);

        return $val;
    }

    /**
     * 验证码删除
     *
     * @param integer $verify_id 验证码id
     * @return bool
     */
    public static function del($verify_id = 0)
    {
        $key = self::key($verify_id);
        $res = Cache::delete($key);

        return $res;
    }
}
