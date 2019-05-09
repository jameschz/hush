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
