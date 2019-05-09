<?php
/**
 * 随机红包算法类
 * @author James
 */
class Core_Util_bonus
{

	/**
	 * 求平方
	 * @param $n
	 */
	function sqr ($n)
	{
		return $n * $n;
	}
	
	/**
	 * 生产min和max之间的随机数，概率从min到max方向概率逐渐加大。
	 * 先平方，然后产生一个平方值范围内的随机数，再开方，这样就产生了一种“膨胀”再“收缩”的效果。
	 */
	function xrand ($bonus_min, $bonus_max)
	{
		$sqr = intval($this->sqr($bonus_max - $bonus_min));
		$rand_num = rand(0, ($sqr-1));
		return intval(sqrt($rand_num));
	}
	
	
	/**
	 * 产生红包算法
	 * @param $bonus_total 红包总额
	 * @param $bonus_count 红包个数
	 * @param $bonus_max 每个小红包的最大额
	 * @param $bonus_min 每个小红包的最小额
	 * @return 存放生成的每个小红包的值的数组
	 */
	function generate ($bonus_total, $bonus_count, $bonus_max, $bonus_min)
	{
		$result = array();
		
		$average = $bonus_total / $bonus_count;
		
		$a = $average - $bonus_min;
		$b = $bonus_max - $bonus_min;
		
		//这样的随机数的概率实际改变了，产生大数的可能性要比产生小数的概率要小。
		//这样就实现了大部分红包的值在平均数附近。大红包和小红包比较少。
		$range1 = $this->sqr($average - $bonus_min);
		$range2 = $this->sqr($bonus_max - $average);
		
		for ($i = 0; $i < $bonus_count; $i++) {
			//因为小红包的数量通常是要比大红包的数量要多的，因为这里的概率要调换过来。
			//当随机数>平均值，则产生小红包
			//当随机数<平均值，则产生大红包
			if (rand($bonus_min, $bonus_max) > $average) {
				// 在平均线上减钱
				$temp = $bonus_min + $this->xrand($bonus_min, $average);
				$result[$i] = $temp;
				$bonus_total -= $temp;
			} else {
				// 在平均线上加钱
				$temp = $bonus_max - $this->xrand($average, $bonus_max);
				$result[$i] = $temp;
				$bonus_total -= $temp;
			}
		}
		// 如果还有余钱，则尝试加到小红包里，如果加不进去，则尝试下一个。
		while ($bonus_total > 0) {
			for ($i = 0; $i < $bonus_count; $i++) {
				if ($bonus_total > 0 && $result[$i] < $bonus_max) {
					$result[$i]++;
					$bonus_total--;
				}
			}
		}
		// 如果钱是负数了，还得从已生成的小红包中抽取回来
		while ($bonus_total < 0) {
			for ($i = 0; $i < $bonus_count; $i++) {
				if ($bonus_total < 0 && $result[$i] > $bonus_min) {
					$result[$i]--;
					$bonus_total++;
				}
			}
		}
		return $result;
	}
}