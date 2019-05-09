<?php 

class Core_Charts
{
	private $_type = '';
	private $_chart = '';
	private $_title = '';
	private $_subtitle = '';
	private $_categories = array();
	private $_xAxis = array();
	private $_yAxis = array();
	private $_tooltip = array();
	private $_plotOptions = array();
	private $_legend = array();
	private $_credits = array();
	private $_series = array();
	private $_unit = 'Value';
	
	public function __construct ($type)
	{
		$this->_type = $type;
	}
	
	public function setUnit ($s)
	{
		$this->_unit = $s;
		return $this;
	}
	
	public function setTitle ($s)
	{
		$this->_title = $s;
		return $this;
	}
	
	public function setSubTitle ($s)
	{
		$this->_subtitle = $s;
		return $this;
	}
	
	public function setCategories ($a)
	{
		$this->_categories = $a;
		return $this;
	}
	
	public function setXAxis ($a)
	{
		$this->_xAxis = $a;
		return $this;
	}
	
	public function setYAxis ($a)
	{
		$this->_yAxis = $a;
		return $this;
	}
	
	public function setTooltip ($a)
	{
		$this->_tooltip = $a;
		return $this;
	}
	
	public function setPlotOptions ($a)
	{
		$this->_plotOptions = $a;
		return $this;
	}
	
	public function setSeries ($a)
	{
		$this->_series = $a;
		return $this;
	}
	
	public function addSerie ($a)
	{
		$this->_series[] = $a;
		return $this;
	}
	
	public function toJson ()
	{
		// draw logic
		switch ($this->_type) {
			case 'area':
				$this->_drawArea();
				break;
			case 'areaspline':
				$this->_drawAreaSpline();
				break;
			case 'time':
				$this->_drawTime();
				break;
			case 'pie':
				$this->_drawPie();
				break;
			case 'bar':
				$this->_drawBar('bar');
			case 'column':
			    $this->_drawBar('column');
				break;
			default:
				return false;
		}
		// return logic
		$data = array(
			'chart' => $this->_chart,
			'title' => array(
				'text' => $this->_title,
			),
			'subtitle' => array(
				'text' => $this->_subtitle,
			),
			'tooltip' => $this->_tooltip,
			'plotOptions' => $this->_plotOptions,
			'series' => $this->_series,
		);
		// for line or area
		if ($this->_xAxis) {
			$data['xAxis'] = $this->_xAxis;
		}
		if ($this->_yAxis) {
			$data['yAxis'] = $this->_yAxis;
		}
		if ($this->_legend) {
			$data['legend'] = $this->_legend;
		}
		if ($this->_credits) {
			$data['credits'] = $this->_credits;
		}
		// build config
		$json = json_encode($data);
		$json = str_replace(array('"#', '#"', '\/'), array('', '', '/'), $json);
		return $json;
	}
	
	private function _drawArea ()
	{
		$this->_chart = array(
			'type' => 'area'
		);
		$this->_xAxis = array(
			'categories' => $this->_categories,
			'allowDecimals' => false,
			'labels' => array(
				'rotation' => -45,
				'formatter' => '#function() { return this.value; }#',
			),
		);
		$this->_yAxis = array(
			'title' => array(
				'text' => $this->_unit,
			),
			'labels' => array(
				'formatter' => "#function() { if (this.value > 1000) return this.value / 1000 + ' k'; else return this.value; }#",
			),
		);
		$this->_tooltip = array(
			'pointFormat' => '{series.name} : {point.y}',
		);
		$this->_plotOptions = array(
			'area' => array(
				'marker' => array(
					'enabled' => false,
					'symbol' => 'circle',
					'radius' => 2,
				),
			),
		);
	}
	
	private function _drawAreaSpline ()
	{
		$this->_drawArea();
		$this->_chart = array(
			'type' => 'areaspline'
		);
	}
	
	private function _drawTime ()
	{
		$this->_chart = array(
            'zoomType' => 'x',
		);
		$this->_xAxis = array(
			'type' => 'datetime',
		    'dateTimeLabelFormats' => array(
		        'millisecond' => '%H:%M:%S.%L',
		        'second' => '%H:%M:%S',
		        'minute' => '%H:%M',
		        'hour' => '%H:%M',
		        'day' => '%m-%d',
		        'week' => '%m-%d',
		        'month' => '%Y-%m',
		        'year' => '%Y',
		    ),
		);
		$this->_yAxis = array(
			'title' => array(
				'text' => $this->_unit,
			),
			'labels' => array(
				'formatter' => "#function() { if (this.value > 1000) return this.value / 1000 + ' k'; else return this.value; }#",
			),
		);
		$this->_tooltip = array(
			'pointFormat' => '{series.name} : {point.y}',
		    'dateTimeLabelFormats' => array(
		        'millisecond' => '%H:%M:%S.%L',
		        'second' => '%H:%M:%S',
		        'minute' => '%H:%M',
		        'hour' => '%H:%M',
		        'day' => '%Y-%m-%d',
		        'week' => '%m-%d',
		        'month' => '%Y-%m',
		        'year' => '%Y',
		    ),
		);
		$this->_plotOptions = array(
			'area' => array(
			    'fillColor' => array(
			        'linearGradient' => array(
			            'x1' => 0,
			            'y1' => 0,
			            'x2' => 0,
			            'y2' => 1,
			        ),
			        'stops' => array(
			            array(0, "#Highcharts.getOptions().colors[0]#"),
			            array(1, "#Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0).get('rgba')#")
			        ),
			    ),
				'marker' => array(
					'enabled' => false,
					'symbol' => 'circle',
					'radius' => 2,
				),
			    'lineWidth' => 1,
			    'states' => array(
			        'hover' => array(
			            'lineWidth' => 1,
			        )
			    ),
			    'threshold' => null,
			),
		);
	}
	
	private function _drawPie ()
	{
		$this->_chart = array(
			'plotBackgroundColor' => null,
			'plotBorderWidth' => null,
			'plotShadow' => false,
		    'type' => 'pie',
		);
		$this->_tooltip = array(
			'pointFormat' => '{series.name} : {point.percentage:.1f}%',
		);
		$this->_plotOptions = array(
			'pie' => array(
				'allowPointSelect' => true,
				'cursor' => 'pointer',
				'showInLegend' => true,
				'dataLabels' => array(
					'enabled' => true,
				),
			),
		);
	}
	
	private function _drawBar ($type='bar')
	{
		$this->_chart = array(
			'type' => $type,
		);
		$this->_xAxis = array(
			'categories' => $this->_categories,
		);
		$this->_yAxis = array(
			'min' => 0,
			'title' => array(
				'text' => $this->_unit,
				'align' => 'high'
			),
			'labels' => array(
				'overflow' => "justify",
			),
		);
		$this->_tooltip = array(
			'valueSuffix' => '',
		);
		$this->_plotOptions = array(
			'bar' => array(
				'dataLabels' => array(
					'enabled' => true,
				),
			),
		);
		$this->_credits = array(
			'enabled' => false,
		);
	}
}