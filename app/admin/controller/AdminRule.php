<?php
/*
 * @Description  : 权限管理
 * @Author       : https://github.com/skyselang
 * @Date         : 2020-03-30
 * @LastEditTime : 2020-09-02
 */

namespace app\admin\controller;

use think\facade\Request;
use app\admin\service\AdminRuleService;
use app\admin\validate\AdminRuleValidate;

class AdminRule
{
    /**
     * 权限列表
     *
     * @method GET
     * 
     * @return json
     */
    public function ruleList()
    {
        $page          = Request::param('page/d', 1);
        $limit         = Request::param('limit/d', 10);
        $sort_field    = Request::param('sort_field/s', '');
        $sort_type     = Request::param('sort_type/s', '');
        $rule_name     = Request::param('rule_name/s', '');
        $rule_desc     = Request::param('rule_desc/s', '');
        $admin_menu_id = Request::param('admin_menu_id/d', 0);

        $where = [];
        if ($rule_name) {
            $where[] = ['rule_name', 'like', '%' . $rule_name . '%'];
        }
        if ($rule_desc) {
            $where[] = ['rule_desc', 'like', '%' . $rule_desc . '%'];
        }
        $whereOr = false;
        if ($admin_menu_id) {
            $whereOr = true;
            $where0 = [['admin_menu_ids', 'like', $admin_menu_id], ['is_delete', '=', 0]];
            $where1 = [['admin_menu_ids', 'like', $admin_menu_id . ',%'], ['is_delete', '=', 0]];
            $where2 = [['admin_menu_ids', 'like', '%,' . $admin_menu_id . ',%'], ['is_delete', '=', 0]];
            $where3 = [['admin_menu_ids', 'like', '%,' . $admin_menu_id], ['is_delete', '=', 0]];
            $where = [$where0, $where1, $where2, $where3];
        }

        $field = '';

        $order = [];
        if ($sort_field && $sort_type) {
            $order = [$sort_field => $sort_type];
        }

        $data = AdminRuleService::list($where, $page, $limit, $field, $order, $whereOr);

        return success($data);
    }

    /**
     * 权限添加
     *
     * @method POST
     * 
     * @return json
     */
    public function ruleAdd()
    {
        $param = Request::only(
            [
                'rule_name'   => '',
                'rule_desc'   => '',
                'rule_sort'   => 200,
                'is_prohibit' => '0',
            ]
        );
        $param['admin_menu_ids'] = Request::param('admin_menu_ids/a', []);

        validate(AdminRuleValidate::class)->scene('rule_add')->check($param);

        $data = AdminRuleService::add($param);

        return success($data);
    }

    /**
     * 权限修改
     *
     * @method POST
     * 
     * @return json
     */
    public function ruleEdit()
    {
        $param = Request::only(
            [
                'admin_rule_id' => 0,
                'rule_name'     => '',
                'rule_desc'     => '',
                'rule_sort'     => 200,
                'is_prohibit'   => '0',
            ]
        );
        $param['admin_menu_ids'] = Request::param('admin_menu_ids/a', []);

        validate(AdminRuleValidate::class)->scene('rule_edit')->check($param);

        $data = AdminRuleService::edit($param);

        return success($data);
    }

    /**
     * 权限删除
     *
     * @method POST
     * 
     * @return json
     */
    public function ruleDele()
    {
        $admin_rule_id = Request::param('admin_rule_id/d', 0);

        validate(AdminRuleValidate::class)->scene('admin_rule_id')->check(['admin_rule_id' => $admin_rule_id]);

        $data = AdminRuleService::dele($admin_rule_id);

        return success($data);
    }

    /**
     * 权限信息
     *
     * @method GET
     * 
     * @return json
     */
    public function ruleInfo()
    {
        $admin_rule_id = Request::param('admin_rule_id/d', 0);

        validate(AdminRuleValidate::class)->scene('admin_rule_id')->check(['admin_rule_id' => $admin_rule_id]);

        $data = AdminRuleService::info($admin_rule_id);

        return success($data);
    }

    /**
     * 权限是否禁用
     *
     * @method POST
     * 
     * @return json
     */
    public function ruleProhibit()
    {
        $admin_rule_id = Request::param('admin_rule_id/d', '');
        $is_prohibit   = Request::param('is_prohibit/s', '0');

        $param['admin_rule_id'] = $admin_rule_id;
        $param['is_prohibit']   = $is_prohibit;

        validate(AdminRuleValidate::class)->scene('admin_rule_id')->check(['admin_rule_id' => $admin_rule_id]);

        $data = AdminRuleService::prohibit($param);

        return success($data);
    }
}
