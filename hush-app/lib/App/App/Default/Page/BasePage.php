<?php
/**
 * App Page
 *
 * @category   App
 * @package    App_App_Default
 * @author     James.Huang <shagoo@gmail.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id$
 */

require_once 'App/App/Default/Page.php';

class BasePageSet
{
    // page setting
    public $page = '';
    public $action = '';
    public $title = 'NULL';
    
    // model setting
    public $pkey = 'id';
    public $model = null;
    public $field = array();
    public $style = array();
    public $filter = array();
    public $search = array();
    public $orders = array();
    public $toptabs = array();
    public $tpldir = 'base/crud';
    public $navimsg = '';
    public $verify_type = 0;
    public $page_num = 20;
    
    // default options
    public $options = array(
        'link_add' => '/a/add',
        'link_edit' => '/a/edit',
        'link_info' => '/a/info',
        'link_verify' => '/a/verify',
        'link_delete' => '/a/delete',
        'name_add' => '添加',
        'name_edit' => '修改',
        'name_info' => '详情',
        'name_verify' => '审核',
        'name_delete' => '删除',
    );
    
    // get filter value
    public function getFilterVal ($key)
    {
        $passed_val = Hush_Util::param($key);
        $default_val = isset($this->filter[$key]['default']) ? $this->filter[$key]['default'] : '';
        return ($passed_val === null) ? $default_val : $passed_val;
    }
}

/**
 * @package App_App_Default
 */
class BasePage extends App_App_Default_Page
{
    public function __init ()
    {
        parent::__init();
        
        // if page need login, uncomment this line
        $this->authenticate();
        
        // init variables
        $this->bps = new BasePageSet();
        $this->bps->style = array('td_op' => 'max-width:200px;line-height:20px;word-break:break-all;');
    }
    
    public function __done ()
    {
        // reset view vars
        $this->view->bps = (array) $this->bps;
        $this->view->field = (array) $this->bps->field;
        
        // reset path
        $class_name = get_class($this);
        $action_path = '/'.strtolower(str_replace('Page','',$class_name)).'/'.$this->bps->action;
        $this->view->action_path = $action_path;
        
        parent::__done();
    }
    
    private function _fill_data ($bps, &$res)
    {
        foreach ($bps->field as $k => $v) {
            // 处理文件结构
            if ($bps->field[$k]['files']) {
                $file_data = (array) @json_decode($res[$k], 1);
                foreach ($bps->field[$k]['files'] as $k1 => $v1) {
                    $v1['value'] = isset($file_data[$k1]) ? $file_data[$k1] : '';
                    $res['_data'][$k][$k1] = $v1;
                }
            }
            // 处理颜色结构
            if ($bps->field[$k]['colors']) {
                $color_data = (array) @json_decode($res[$k], 1);
                foreach ($bps->field[$k]['colors'] as $k1 => $v1) {
                    $v1['value'] = isset($color_data[$k1]) ? $color_data[$k1] : '';
                    $res['_data'][$k][$k1] = $v1;
                }
            }
            // 处理模型结构（若list_only为真则不处理）
            if ($bps->field[$k]['model'] && !$bps->field[$k]['model']['list_only']) {
                $model = $bps->field[$k]['model']['table'];
                $where = $bps->field[$k]['model']['where'];
                $id_prim = $bps->field[$k]['model']['id_prim'];
                $id_list = $bps->field[$k]['model']['id_list'];
                if ($where) {
                    $where = str_replace(
                        array('{pkey}'),
                        array($res[$bps->pkey]),
                        $where
                        );
                    $mdata = $this->dao->load($model)->find('*', $where);
                } else {
                    $mdata = $this->dao->load($model)->find();
                }
                // 变成MAP结构
                $select = array();
                foreach ($mdata as $mrow) {
                    $select[$mrow[$id_prim]] = $mrow[$id_list];
                }
                $res['_data'][$k] = $select;
            }
            // 处理选择数据
            if ($bps->field[$k]['msel']) {
                // 左侧数据
                $l_model = $bps->field[$k]['msel']['l_table'];
                $l_where = $bps->field[$k]['msel']['l_where'];
                $l_key_id = $bps->field[$k]['msel']['l_key_id'];
                $l_key_name = $bps->field[$k]['msel']['l_key_name'];
                if ($l_where) {
                    $l_where = str_replace(
                        array('{pkey}'),
                        array($res[$bps->pkey]),
                        $l_where
                        );
                    $l_data = $this->dao->load($l_model)->find('*', $l_where);
                } else {
                    $l_data = $this->dao->load($l_model)->find();
                }
                if ($l_data) {
                    $select = array();
                    foreach ($l_data as $l_row) {
                        $select[$l_row[$l_key_id]] = $l_row[$l_key_name];
                    }
                    $res['_data']['_msel_l'][$k] = $select;
                }
                // 右侧数据
                if ($res[$bps->pkey]) {
                    $r_model = $bps->field[$k]['msel']['r_table'];
                    $r_where = $bps->field[$k]['msel']['r_where'];
                    $r_key_id = $bps->field[$k]['msel']['r_key_id'];
                    if ($r_where) {
                        $r_where = str_replace(
                            array('{pkey}'),
                            array($res[$bps->pkey]),
                            $r_where
                            );
                        $r_data = $this->dao->load($r_model)->find('*', $r_where);
                    }
                    if ($r_data) {
                        $select = array();
                        foreach ($r_data as $r_row) {
                            $r_sel_id = $r_row[$r_key_id];
                            $select[$r_sel_id] = $res['_data']['_msel_l'][$k][$r_sel_id];
                        }
                        $res['_data']['_msel_r'][$k] = $select;
                    }
                }
            }
        }
    }
    
    private function _list_data ($bps, &$res)
    {
        foreach ($bps->field as $k => $v) {
            // 处理文件结构
            if ($bps->field[$k]['files']) {
                $file_data = (array) json_decode($res[$k], 1);
                foreach ($bps->field[$k]['files'] as $k1 => $v1) {
                    $v1['value'] = isset($file_data[$k1]) ? $file_data[$k1] : '';
                    $res['_data'][$k][$k1] = $v1;
                }
            }
            // 处理颜色结构
            if ($bps->field[$k]['colors']) {
                $color_data = (array) json_decode($res[$k], 1);
                foreach ($bps->field[$k]['colors'] as $k1 => $v1) {
                    $v1['value'] = isset($color_data[$k1]) ? $color_data[$k1] : '';
                    $res['_data'][$k][$k1] = $v1;
                }
            }
            // 处理模型结构
            if ($bps->field[$k]['model']) {
                $model = $bps->field[$k]['model']['table'];
                $where = $bps->field[$k]['model']['where'];
                $id_prim = $bps->field[$k]['model']['id_prim'];
                $id_list = $bps->field[$k]['model']['id_list'];
                $dao_m = $this->dao->load($model);
                if ($dao_m) {
                    $where = $dao_m->dbr()->quoteInto("{$id_prim}=?", $res[$k]);
                    $mdata = $this->dao->load($model)->find('*', $where);
                }
                // 变成MAP结构
                $select = array();
                foreach ($mdata as $mrow) {
                    $select[$mrow[$id_prim]] = $mrow[$id_list];
                }
                $res['_data'][$k] = $select;
            }
        }
    }
    
    private function _has_image ($v = array())
    {
        foreach ((array) $v as $d) {
            if (preg_match('/^data:image/i', $d)) {
                return true;
            }
        }
        return false;
    }
    
    protected function _info (BasePageSet $bps)
    {
        if (method_exists($this, '_before_info')) {
            $res = call_user_func_array(array($this, '_before_info'), array($res));
        }
        $dao = $this->dao->load($bps->model);
        $res = $dao->read($this->param($bps->pkey), $bps->pkey);
        $this->_list_data($bps, $res);
// 		Hush_Util::dump($res);
        if (method_exists($this, '_after_info')) {
            $res = call_user_func_array(array($this, '_after_info'), array($res));
        }
        $this->view->item = (array) $res;
    }
    
    protected function _add (BasePageSet $bps)
    {
        // add action
        if ($_POST) {
            // get form
            $form = array();
            foreach ($bps->field as $k => $v) {
                // 文件参数先去除，后面会在处理文件上传时补充
                if ($v['type'] == 'file') continue;
                if ($v['add'] && !in_array($k, $form)) $form[] = $k;
            }
            $data = $this->getPostData($form, $bps->field);
            // 按格式处理
            if ($this->noError()) {
                foreach ($data as $k => $v) {
                    if ($bps->field[$k]['type'] == 'date' || $bps->field[$k]['type'] == 'time') {
                        $data[$k] = strtotime($v);
                    } elseif ($bps->field[$k]['type'] == 'color') {
                        $data[$k] = json_encode($v);
                    } elseif ($bps->field[$k]['type'] == 'pass') {
                        $data[$k] = Core_Util::md5($v);
                    } elseif ($bps->field[$k]['model'] && $v) {
                        $model = $bps->field[$k]['model']['table'];
                        $mokey = $bps->field[$k]['model']['id_prim'];
                        $mdata = $this->dao->load($model)->read($v, $mokey);
                        if (!$mdata) {
                            $this->addError('common.err.noexisted', $mokey);
                        }
                    }
                }
            }
            // 处理文件上传
            if ($this->noError()) {
                // 准备上传数据
                $cdn_dir = cfg('app.upload.pics.dir');
                $cdn_url = cfg('app.upload.pics.url');
                if (!is_dir($cdn_dir)) mkdir($cdn_dir, 0777, true);
                // 二进制文件上传
                $file_data = array();
                if ($_FILES) {
                    foreach ($_FILES as $k => $v) {
                        foreach ($v['name'] as $k1 => $v1) {
                            $upload_file_name = Core_Util::cdn_rename_file($v1);
                            $upload_file_ext = Core_Util::core_file_ext($upload_file_name);
                            $upload_file_data = Core_Util::core_file_init($cdn_dir, $cdn_url, $upload_file_ext);
                            $src_file = $_FILES[$k]['tmp_name'][$k1];
                            $dst_file = $upload_file_data['dir'];
                            if (Core_Util::core_file_move($src_file, $dst_file)) {
                                $file_data[$k1] = $upload_file_data['url'];
                            }
                        }
                        if ($file_data) {
                            ksort($file_data);
                            $data[$k] = json_encode($file_data);
                        }
                    }
                }
                // 压缩图片上传
                if ($_POST) {
                    foreach ($_POST as $k => $v) {
                        if ($this->_has_image($v)) {
                            $file_data = isset($data[$k]) ? json_decode($data[$k], 1) : array();
                            foreach ((array) $v as $k1 => $v1) {
                                $upload_file_data = Core_Util::core_file_save($v1);
                                if ($upload_file_data) {
                                    $file_data[$k1] = $upload_file_data['url'];
                                }
                            }
                            if ($file_data) {
                                ksort($file_data);
                                $data[$k] = json_encode($file_data);
                            }
                        }
                    }
                }
            }
            // 处理数据记录
            if ($this->noError()) {
                try {
                    // 数据事务开始
                    $dao = $this->dao->load($bps->model);
                    Core_Service::trans_begin($dao);
                    // 前置回调方法
                    if (method_exists($this, '_before_add')) {
                        $data = call_user_func_array(array($this, '_before_add'), array($data));
                        if (!$this->noError()) {
                            throw new Exception('_before_add error');
                        }
                    }
                    // 数据创建逻辑
                    $data['dtime'] = time(); // update time
                    $cid = $dao->create($data);
                    // 不是自增主键时可能没有返回值
                    if ($cid) {
                        $data[$bps->pkey] = $cid;
                    }
                    // 后置回调方法
                    if (method_exists($this, '_after_add')) {
                        $data = call_user_func_array(array($this, '_after_add'), array($data));
                    }
                    $this->addOk('common.msg.success');
                    Core_Service::trans_commit($dao);
                    $this->add_ok = true;
                } catch (Exception $e) {
// 					echo '[ADD] : '.$e->getMessage();
// 					$this->addError('common.msg.failed');
                    Core_Service::trans_rollback($dao);
                }
            }
        }
        
        // 获取默认值
        $res = (array) $data;
        $this->_fill_data($bps, $res);
// 		Hush_Util::dump($res);
        $this->view->item = (array) $res;
        $this->view->title = $bps->title;
        $this->view->field = $bps->field;
    }
    
    protected function _edit (BasePageSet $bps)
    {
        // edit action
        if ($_POST) {
            // get form
            $form = array($bps->pkey);
            foreach ($bps->field as $k => $v) {
                // 文件参数先去除，后面会在处理文件上传时补充
                if ($v['type'] == 'file') continue;
                if ($v['edit'] && !in_array($k, $form)) $form[] = $k;
            }
            $data = $this->getPostData($form, $bps->field);
            // 按格式处理
            if ($this->noError()) {
                foreach ($data as $k => $v) {
                    if ($bps->field[$k]['type'] == 'date' || $bps->field[$k]['type'] == 'time') {
                        $data[$k] = strtotime($v);
                    } elseif ($bps->field[$k]['type'] == 'color') {
                        $data[$k] = json_encode($v);
                    } elseif ($bps->field[$k]['type'] == 'pass') {
                        $data[$k] = Core_Util::md5($v);
                    } elseif ($bps->field[$k]['model'] && $v) {
                        $model = $bps->field[$k]['model']['table'];
                        $mokey = $bps->field[$k]['model']['id_prim'];
                        $mdata = $this->dao->load($model)->read($v, $mokey);
                        if (!$mdata) {
                            $this->addError('common.err.noexisted', $mokey);
                        }
                    }
                }
            }
            // 处理文件上传
            if ($this->noError()) {
                // 获取原始数据
                $dao = $this->dao->load($bps->model);
                $res = (array) $dao->read($this->param($bps->pkey), $bps->pkey);
                $cdn_dir = cfg('app.upload.pics.dir');
                $cdn_url = cfg('app.upload.pics.url');
                if (!is_dir($cdn_dir)) mkdir($cdn_dir, 0777, true);
                // 二进制文件上传
                $file_data = array();
                if ($_FILES) {
                    foreach ($_FILES as $k => $v) {
                        $file_data = isset($res[$k]) ? json_decode($res[$k], 1) : array();
                        foreach ($v['name'] as $k1 => $v1) {
                            $upload_file_name = Core_Util::cdn_rename_file($v1);
                            $upload_file_ext = Core_Util::core_file_ext($upload_file_name);
                            $upload_file_data = Core_Util::core_file_init($cdn_dir, $cdn_url, $upload_file_ext);
                            $src_file = $_FILES[$k]['tmp_name'][$k1];
                            $dst_file = $upload_file_data['dir'];
                            if (Core_Util::core_file_move($src_file, $dst_file)) {
                                $file_data[$k1] = $upload_file_data['url'];
                                // getid3 for audio and video - add by james
                                $file_info = Core_Util::core_getid3($dst_file);
                                if ($file_info) {
                                    if (isset($file_info['playtime_seconds'])) {
                                        $file_data[$k1.'_ps'] = intval($file_info['playtime_seconds']);
                                    }
                                }
                            }
                        }
                        if ($file_data) {
                            ksort($file_data);
                            $data[$k] = json_encode($file_data);
                        }
                    }
                }
                // 压缩图片上传
                if ($_POST) {
                    foreach ($_POST as $k => $v) {
                        if ($this->_has_image($v)) {
                            $file_data = isset($data[$k]) ? json_decode($data[$k], 1) : array();
                            if (!$file_data) {
                                $file_data = isset($res[$k]) ? json_decode($res[$k], 1) : array();
                            }
                            foreach ((array) $v as $k1 => $v1) {
                                $upload_file_data = Core_Util::core_file_save($v1);
                                if ($upload_file_data) {
                                    $file_data[$k1] = $upload_file_data['url'];
                                }
                            }
                            if ($file_data) {
                                ksort($file_data);
                                $data[$k] = json_encode($file_data);
                            }
                        }
                    }
                }
            }
            // 处理数据记录
            if ($this->noError()) {
                try {
                    // 数据事务开始
                    $dao = $this->dao->load($bps->model);
                    Core_Service::trans_begin($dao);
                    // 前置回调方法
                    if (method_exists($this, '_before_edit')) {
                        $data = call_user_func_array(array($this, '_before_edit'), array($data));
                        if (!$this->noError()) {
                            throw new Exception('_before_edit error');
                        }
                    }
                    // 数据更新逻辑
                    $data[$bps->pkey] = $this->param($bps->pkey);
                    $data['dtime'] = time(); // update time
                    $dao->update($data, $bps->pkey);
                    // 后置回调方法
                    if (method_exists($this, '_after_edit')) {
                        $data = call_user_func_array(array($this, '_after_edit'), array($data));
                    }
                    $this->addOk('common.msg.success');
                    Core_Service::trans_commit($dao);
                    $this->edit_ok = true;
                } catch (Exception $e) {
//                     echo '[EDIT] : '.$e->getMessage();
//                     $this->addError('common.msg.failed');
                    Core_Service::trans_rollback($dao);
                }
            }
        }
        
        // 获取数据库值
        $dao = $this->dao->load($bps->model);
        $res = (array) $dao->read($this->param($bps->pkey), $bps->pkey);
        $this->_fill_data($bps, $res);
        // 		Hush_Util::dump($res);
        $this->view->item = (array) $res;
        $this->view->title = $bps->title;
        $this->view->field = $bps->field;
    }
    
    protected function _verify (BasePageSet $bps)
    {
        // verify action
        if ($_POST) {
            // add aps field
            $bps->field['result'] = array('type' => 'text', 'name' => '审核原因');
            // get form
            $form = array($bps->pkey, 'status', 'result');
            $data = $this->getPostData($form, $bps->field);
            if ($this->noError()) {
                try {
                    // 数据事务开始
                    $dao = $this->dao->load($bps->model);
                    Core_Service::trans_begin($dao);
                    // 前置回调方法
                    if (method_exists($this, '_before_verify')) {
                        $data = call_user_func_array(array($this, '_before_verify'), array($data));
                        if (!$this->noError()) {
                            throw new Exception('_before_verify error');
                        }
                    }
                    // 更新状态
                    $time = time();
                    $result = $data['result'];
                    unset($data['result']);
                    $data['dtime'] = $time;
                    $dao->update($data, $bps->pkey);
                    // 后置回调方法
                    if (method_exists($this, '_after_verify')) {
                        $data = call_user_func_array(array($this, '_after_verify'), array($data));
                    }
                    $this->addOk('common.msg.success');
                    Core_Service::trans_commit($dao);
                } catch (Exception $e) {
//                     echo '[VERIFY] : '.$e->getMessage();
//                     $this->addError('common.msg.failed');
                    Core_Service::trans_rollback($dao);
                }
            }
        }
        
        $dao = $this->dao->load($bps->model);
        $res = (array) $dao->read($this->param($bps->pkey), $bps->pkey);
        $this->view->item = (array) $res;
        $this->view->title = $bps->title;
        $this->view->field = $bps->field;
    }
    
    protected function _delete (BasePageSet $bps)
    {
        // delete ajax logic
        if ($_POST) {
            // 删除文件
            $file_id = $this->param('_file_id');
            $file_col = $this->param('_file_col');
            $file_name = $this->param('_file_name');
            if ($file_id && $file_col && $file_name) {
                // 处理文件删除
                $dao = $this->dao->load($bps->model);
                $res = (array) $dao->read($file_id, $bps->pkey);
                $file_data = (array) json_decode($res[$file_col],1);
                if (!$res || !$file_data) {
                    Core_Util::app_ajax_result(ERR_SYS, '数据不存在');
                }
                foreach ($file_data as $k => $v) {
                    if ($k == $file_name) {
                        $file_dir = Core_Util::cdn_url2dir($v);
                        @unlink($file_dir);
                        unset($file_data[$k]);
                        unset($file_data[$k.'_ps']);
                    }
                }
                try {
                    $res[$file_col] = json_encode($file_data);
                    $dao->update($res);
                    Core_Util::app_ajax_result(ERR_OK, '');
                } catch (Exception $e) {
                    Core_Util::app_ajax_result(ERR_SYS, '');
                }
            }
            Core_Util::app_ajax_result(ERR_SYS, '');
            // 删除数据
            Core_Util::app_ajax_result(ERR_SYS, '');
        }
        // delete main logic
        else {
            $del_id = $this->param($bps->pkey);
            if ($del_id) {
                try {
                    // 数据事务开始
                    $dao = $this->dao->load($bps->model);
                    Core_Service::trans_begin($dao);
                    // 前置回调方法
                    $data = $dao->read($del_id, $bps->pkey);
                    if (method_exists($this, '_before_delete')) {
                        $data = call_user_func_array(array($this, '_before_delete'), array($data));
                        if (!$this->noError()) {
                            throw new Exception('_before_delete error');
                        }
                    }
                    // 判断是否删除
                    if ($data) {
                        $dao->delete($del_id, $bps->pkey);
                    }
                    // 后置回调方法
                    if (method_exists($this, '_after_delete')) {
                        $data = call_user_func_array(array($this, '_after_delete'), array($data));
                    }
                    Core_Service::trans_commit($dao);
                    // 返回来源页
                    if ($data) {
                        $this->forward($_SERVER['HTTP_REFERER']);
                    }
                } catch (Exception $e) {
//                     echo '[DELETE] : '.$e->getMessage();
//                     $this->addError('common.msg.failed');
                    Core_Service::trans_rollback($dao);
                }
            }
        }
    }
    
    public function _export (BasePageSet $bps)
    {
        
    }
    
    public function _crud (BasePageSet $bps, $need_list = true)
    {
        // view vars
        $this->view->title = $bps->title;
        $this->view->field = $bps->field;
        $this->view->filter = $bps->filter;
        
        // info action
        if ($this->param('a') == 'info') {
            $this->_info($bps);
            return $this->render($bps->tpldir.'/info.tpl');
        }
        
        // add action
        if ($this->param('a') == 'add') {
            $this->_add($bps);
            return $this->render($bps->tpldir.'/add.tpl');
        }
        
        // edit action
        if ($this->param('a') == 'edit') {
            $this->_edit($bps);
            return $this->render($bps->tpldir.'/edit.tpl');
        }
        
        // verify action
        if ($this->param('a') == 'verify') {
            $this->_verify($bps);
            return $this->render($bps->tpldir.'/verify.tpl');
        }
        
        // delete action
        if ($this->param('a') == 'delete') {
            $this->_delete($bps);
            return $this->render($bps->tpldir.'/delete.tpl');
        }
        
        // export action
        if ($this->param('a') == 'export') {
            return $this->_export($bps);
        }
        
        // list action
        if ($need_list) {
            
            // get search array
            $search = (array) $bps->search;
            foreach ($bps->filter as $k => $v) {
                // add filter into search
                if (!isset($search[$k])) {
                    $fv = $bps->getFilterVal($k);
                    $search[$k] = $fv;
                }
            }
            $this->view->search = $search;
            
            // get order links
            $sel_order_k = '';
            $sel_order_d = '';
            $page_orders = array();
            $view_orders = array();
            $param_orders = (array) json_decode(base64_decode($this->param('_orders')));
            if ($param_orders) {
                foreach ($param_orders as $k => $v) {
                    $order_by = $v ? $v : 'desc';
                    if ($order_by) {
                        $sel_order_k = $k;
                        $sel_order_d = $order_by;
                        $page_orders[] = $k . ' ' . $order_by;
                        $view_orders[$k]['sign'] = ($order_by == 'desc') ? '<img src="/img/sort_desc.gif">' : '<img src="/img/sort_asc.gif">';
                    }
                }
            } else {
                foreach ((array) $bps->orders as $k => $v) {
                    $order_by = isset($v['order']) ? $v['order'] : false;
                    if ($order_by) {
                        $page_orders[] = $k . ' ' . $v['order'];
                        if (isset($v['icon']) && $v['icon']) {
                            $sel_order_k = $k;
                            $sel_order_d = $order_by;
                            $view_orders[$k]['sign'] = ($order_by == 'desc') ? '<img src="/img/sort_desc.gif">' : '<img src="/img/sort_asc.gif">';
                        }
                    }
                }
            }
            $path_base = isset($this->path['path']) ? trim($this->path['path']) : '';
            $path_pars = array();
            if (isset($this->path['query'])) {
                parse_str($this->path['query'], $path_pars);
            }
            foreach ((array) $bps->orders as $k => $v) {
                $order_by = isset($v['order']) ? $v['order'] : 'desc';
                $order_arr = array();
                $order_arr[$k] = $order_by;
                if ($sel_order_k == $k) {
                    $order_arr[$k] = ($sel_order_d == 'desc') ? 'asc' : 'desc';
                }
                $path_pars['_orders'] = base64_encode(json_encode($order_arr));
                $path_pars['p'] = 1; // back to first page
                $view_orders[$k]['link'] = $path_base.'?'.http_build_query($path_pars);
            }
            $this->view->page_orders = $page_orders;//Hush_Util::dump($page_orders);
            $this->view->view_orders = $view_orders;//Hush_Util::dump($view_orders);
            
            // list action
            if (method_exists($this, '_before_list')) {
                list($search, $page_orders) = call_user_func_array(array($this, '_before_list'), array($search, $page_orders));
            }
            
            // search by default
            $dao = $this->dao->load($bps->model);
            if ($bps->page_num) {
                $res = $dao->page($search, null, $bps->page_num, $page_orders);
                if ($res) {
                    $list = (array) $res['list'];
                    $page = (array) $res['page'];
                    foreach ($list as &$data) {
                        $this->_list_data($bps, $data);
                    }
                    if (method_exists($this, '_after_list')) {
                        $list = call_user_func_array(array($this, '_after_list'), array($list));
                    }
                    $this->view->result = $list;
                    $this->view->paging = $page;
                }
            } else {
                $res = $dao->search($search, null, $page_orders);
                if ($res) {
                    $list = (array) $res;
                    foreach ($list as &$data) {
                        $this->_list_data($bps, $data);
                    }
                    if (method_exists($this, '_after_list')) {
                        $list = call_user_func_array(array($this, '_after_list'), array($list));
                    }
                    $this->view->result = $list;
                }
            }
        }
    }
    
    public function _stat (BasePageSet $bps)
    {
        // view vars
        $this->view->title = $bps->title;
        $this->view->field = $bps->field;
        $this->view->filter = $bps->filter;
        
        // get search array
        $cond = $search = (array) $bps->search;
        foreach ($bps->filter as $k => $v) {
            // add filter into search
            if (!isset($search[$k])) {
                $fv = $bps->getFilterVal($k);
                $search[$k] = $fv;
            }
            // add stat condition
            if ($v['cond'] && $fv) {
                $cond[$k] = $fv;
            }
        }
        $this->view->search = $search;
        
        // get chart data
        if ($this->bps->chart) {
            // get time data
            $sday = min($search['sday'], $search['eday']);
            $eday = max($search['sday'], $search['eday']);
            $days = Core_Util::app_day_range($sday, $eday);
            if ($this->bps->model) {
                // get data from dao
                $dao_etl = $this->dao->load($this->bps->model);
                $chart_data = (array) $dao_etl->getChartData($days, $cond);
            } else {
                // get data from code
                $chart_data = (array) $this->bps->chart['datas'];
            }
            $ticks = isset($chart_data['ticks']) ? $chart_data['ticks'] : array();
            $datas = isset($chart_data['datas']) ? $chart_data['datas'] : array();
            $tables = isset($chart_data['tables']) ? $chart_data['tables'] : array();
            $totals = isset($chart_data['totals']) ? $chart_data['totals'] : array();
            // do with charts
            if ($datas) {
                // fill categories
                $categories = array();
                if ($ticks) {
                    foreach ((array) $ticks as $tick) {
                        $categories[] = $tick;
                    }
                }
                // fill series
                $series = array();
                foreach ((array) $this->bps->chart['series'] as $k => $v) {
                    $sdata = $v['data']; // 字段标识
                    $sname = $v['name']; // 字段名称
                    $serie = array(
                        'name' => $sname,
                        'data' => array(),
                    );
                    if (isset($datas[$sdata])) {
                        foreach ((array) $datas[$sdata] as $data) {
                            $serie['data'][] = $data;
                        }
                        $series[] = $serie;
                    }
                }
                // show charts
                $charts = null;
                switch ($this->bps->chart['type']) {
                    case 'area':
                        require_once 'Core/Charts.php';
                        $charts = new Core_Charts('areaspline');
                        break;
                    case 'time':
                        require_once 'Core/Charts.php';
                        $charts = new Core_Charts('time');
                        foreach ($series as &$serie) {
                            $serie['type'] = 'area';
                        }
                        break;
                    case 'bar':
                        require_once 'Core/Charts.php';
                        $charts = new Core_Charts('bar');
                        break;
                    case 'pie':
                        require_once 'Core/Charts.php';
                        $charts = new Core_Charts('pie');
                        break;
                }
                if ($charts) {
                    $charts->setTitle($this->bps->chart['title']);
                    $charts->setSubTitle($this->bps->chart['subtitle']);
                    $charts->setCategories($categories);
                    $charts->setSeries($series);
                    $this->view->charts = $charts->toJson();
                    if ($this->param('_get_json')) {
                        exit($this->view->charts);
                    }
                }
            }
            // do with table
            if ($tables && $totals) {
                $this->view->tables = $tables;
                $this->view->totals = $totals;
            }
        }
    }
}
