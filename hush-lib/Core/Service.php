<?php
require_once 'Core/Util.php';
require_once 'Core/Dao.php';

////////////////////////////////////////////////////////////////////////////////////////////////////
// base define

// 定义bc精度
bcscale(2);

////////////////////////////////////////////////////////////////////////////////////////////////////
// base class

class Core_Service
{
    /**
     * Autoload App Daos
     *
     * @param string $class_name
     * @return App_Dao
     */
    public static function load ($class_name)
    {
        static $_daos = array();
        if (!isset($_daos[$class_name])) {
            require_once 'App/Service/' . str_replace('_', '/', $class_name) . '.php';
            $_daos[$class_name] = new $class_name();
        }
        return $_daos[$class_name];
    }
    
	/**
	 * 格式化数字
	 * @param $num float
	 * @return float
	 */
	public static function m_num ($num)
	{
		return sprintf("%.2f", $num);
	}
	
	/**
	 * 多个数相加
	 * @return float
	 */
	public static function m_add ()
	{
		$total = 0;
		$nums = func_get_args();
		if ($nums) {
			foreach ($nums as $num) {
				$total = bcadd($total, $num);
			}
		}
		return self::m_num($total);
	}
	
	/**
	 * 异常处理
	 * @param string $msg
	 * @throws Exception
	 */
	public static function exception ($msg)
	{
		// 排除多次捕获异常时造成的的前缀重复问题
		$msg = '[Core_Service] '.str_replace('[Core_Service] ', '', $msg);
		throw new Exception($msg);
	}

	/**
	 * 事务开始
	 * @param string $dao
	 */
	public static function trans_begin ($dao = null)
	{
	    $dao = $dao ? $dao : Core_Dao::load('Core_App');
	    $dao->beginTransaction();
	}
	
	/**
	 * 事务提交
	 */
	public static function trans_commit ($dao = null)
	{
	    $dao = $dao ? $dao : Core_Dao::load('Core_App');
	    $dao->commit();
	}
	
	/**
	 * 事务回滚
	 */
	public static function trans_rollback ($dao = null)
	{
	    $dao = $dao ? $dao : Core_Dao::load('Core_App');
	    $dao->rollback();
	}
}
