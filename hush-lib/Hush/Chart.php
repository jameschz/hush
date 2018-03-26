<?php
/**
 * Hush Framework
 *
 * @category   Hush
 * @package    Hush_Chart
 * @author     James.Huang <shagoo@gmail.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id$
 */
 
error_reporting(E_ALL ^ E_NOTICE);

// Standard inclusions   
include_once 'Hush/Chart/pChart/pData.class.php';
include_once 'Hush/Chart/pChart/pChart.class.php';

define('__FONT_DIR', realpath(dirname(__FILE__).'/Chart/Fonts'));
define('__FONT_NAME', 'ARIAL.TTF');

/**
 * @package Hush_Chart
 */
class Hush_Chart
{
	/**
	 * @var pData
	 */
	public $data;
	
	/**
	 * @var pChart
	 */
	public $chart;
	
	/**
	 * @var int
	 */
	public $skip = 1;
	
	/**
	 * @var bool
	 */
	public $margin = false;
	
	/**
	 * Chart title name
	 * @var string
	 */
	public $title = null;
	
	/**
	 * Chart label name
	 * @var string
	 */
	public $label = null;
	
	/**
	 * Chart data array
	 * @var array
	 */
	public $cdata = array();
	
	/**
	 * Chart Series array
	 * @var array
	 */
	public $serie = array();
	
	/**
	 * Show Series array
	 * @var array
	 */
	public $shows = array();
	
	/**
	 * Chart font style
	 * @var string
	 */
	public $fontn = __FONT_NAME;
	
	/**
	 * Default image width
	 * @var int
	 */
	public $w = 100;		// image width
	
	/**
	 * Default image height
	 * @var int
	 */
	public $h = 100;		// image height
	
	/**
	 * Default image styles
	 * @var array
	 */
	public $p = array();	// image styles
	
	/**
	 * Chart type
	 * Contain 'line', 'bar', 'pie'
	 * @var string
	 */
	public $type = null;
	
	/**
	 * Valid chart types
	 * @var array
	 */
	public $types = array('line', 'bar', 'pie');
	
	/**
	 * Magic method
	 * Use to call pChart.class's method
	 */
	public function __call ($method, $arguments)
	{
		if (is_object($this->chart)) {
			if (method_exists($this->chart, $method)) {
				return call_user_func_array(array($this->chart, $method), $arguments);
			}
		}
		return false;
	}
	
	/**
	 * Contruct
	 * @param string $t Chart name
	 * @param int $w Chart width
	 * @param int $h Chart height
	 * @param array $p Chart options, Different from chart types
	 */
	public function __construct ($t, $w, $h, $p = array()) 
	{
		if (!in_array($t, $this->types)) {
			require_once 'Hush/Chart/Exception.php';
			throw new Hush_Chart_Exception('Unsupported chart type \'' . $t . '\'');
		} else {
			$this->type = $t;
		}
		$this->data = new pData();
		$this->chart = new pChart($w, $h); // width & height
		$this->w = $w;
		$this->h = $h;
		$this->p = $p;
	}
	
	/**
	 * Set chart title
	 * @param string $title
	 */
	public function setTitle ($title) 
	{
		$this->title = $title;
		return $this;
	}
	
	/**
	 * Set chart font
	 * @param string $font
	 */
	public function setFont ($font)
	{
		$this->fontn = $font;
		return $this;
	}
	
	/**
	 * Add Chart data
	 * @param array $data
	 * @param string $serie
	 */
	public function addData ($data = array(), $serie) 
	{
//		if (!$data || !is_array($data)) {
//			require_once 'Hush/Chart/Exception.php';
//			throw new Hush_Chart_Exception('Data must be an array, please check');
//		}
		$this->data->AddPoint($data, $serie);
		$this->cdata += $data; // add into data storage
		$this->serie[] = $serie; // add into serie storage
		return $this;
	}
	
	/**
	 * Show serie's value
	 * @param string $serie
	 */
	public function showSerie ($serie)
	{
		if ($this->_checkSerieExists($serie)) {
			$this->shows[] = $serie;
		}
		return $this;
	}
	
	/**
	 * Set standard label serie
	 * @param string $serie
	 */
	public function setLabelSerie ($serie)
	{
		if ($this->_checkSerieExists($serie)) {
			$this->label = $serie;
		}
		return $this;
	}
	
	/**
	 * Set chart scale
	 * @param array $data
	 * @param int $skip Used by clicks chart
	 * @param bool $margin
	 */
	public function setScale ($data = array(), $skip = 1, $margin = false)
	{
		// scale using data
		$scale_data = array();
		foreach ($data as $_) {
			for ($i = 0; $i < $skip; $i++) {
				$scale_data[] = $_;
			}
		}
		//print_r($scale_data);
		// add default scale
		$this->addData($scale_data,'DEFAULT_SCALE');
		$this->setLabelSerie('DEFAULT_SCALE');
		$this->margin = $margin;
		$this->skip = $skip;
		return $this;
	}
	
	/**
	 * Display chart directly
	 * @return unknown
	 */
	public function show ()
	{
		if (empty($this->cdata)) {
			require_once 'Hush/Chart/Exception.php';
			throw new Hush_Chart_Exception('Empty data exception, please add data first');
		}$this->showValue('score');
		$method = 'draw' . ucfirst($this->type);
		if (method_exists($this, $method)) {
			$this->$method();
			// insert show serie logic
			if (count($this->shows) > 0) {
				foreach ($this->shows as $serie) {
					$this->chart->writeValues($this->data->GetData(),$this->data->GetDataDescription(), $serie);
				}
			}
			$this->chart->Stroke();
		}
	}
	
	/**
	 * Save chart as picture
	 * @param string $pic
	 * @return unknown
	 */
	public function save ($pic)
	{
		if (empty($this->cdata)) {
			require_once 'Hush/Chart/Exception.php';
			throw new Hush_Chart_Exception('Empty data exception, please add data first');
		}
		$method = 'draw' . ucfirst($this->type);
		if (method_exists($this, $method)) {
			$this->$method();
			$this->chart->render($pic);
		}
	}
	
	/**
	 * Draw line style chart
	 * @return unknown
	 */
	protected function drawLine () 
	{
		// prepare font & series
		$this->_prepareSerie();
		$this->_prepareFont();
		
		// init chart params
		$outer_w	= $this->w - 5;		// Outer frame witdh
		$outer_h	= $this->h - 5;		// Outer frame heigth
		$inner_w	= $this->w - 7;		// Inner frame witdh
		$inner_h	= $this->h - 7;		// Inner frame heigth
		$chart_w	= $this->w - 150;	// Chart frame witdh
		$chart_h	= $this->h - 40;	// Chart frame heigth
		$title_w	= $this->w - 200;	// Title width
		$title_h	= 45;				// Title height
		$legend_w	= $chart_w + 30;	// Legend width
		$legend_h	= 40;				// Legend height
		
		// chart styles
		$grid	= isset($this->p['grid']) ? $this->p['grid'] : false;
		$plot	= isset($this->p['plot']) ? $this->p['plot'] : false;
		$curve	= isset($this->p['curve']) ? $this->p['curve'] : false;
		$filled	= isset($this->p['filled']) ? $this->p['filled'] : false;
		
		// fill chart
		$this->chart->drawBackground(255,255,255);
		$this->chart->setFontProperties($this->font,7); // set font and size
		$this->chart->drawRoundedRectangle(5,5,$outer_w,$outer_h,10,230,230,230); // drawRoundedRectangle($X1,$Y1,$X2,$Y2,$Radius,$R,$G,$B)  
		$this->chart->drawFilledRoundedRectangle(7,7,$inner_w,$inner_h,10,240,240,240); // drawRoundedRectangle($X1,$Y1,$X2,$Y2,$Radius,$R,$G,$B)  
		$this->chart->setGraphArea(70,40,$chart_w,$chart_h); // setGraphArea($X1,$Y1,$X2,$Y2) 
		$this->chart->drawGraphArea(255,255,255,TRUE); // drawGraphArea($R,$G,$B)
		$this->chart->drawScale($this->data->GetData(),$this->data->GetDataDescription(),SCALE_NORMAL,150,150,150,TRUE,0,2,$this->margin,$this->skip); // drawScale($Data,$DataDescription,$ScaleMode,$R,$G,$B,$DrawTicks=TRUE,$Angle=0,$Decimals=1,$WithMargin=FALSE,$SkipLabels=1,$RightScale=FALSE)  
		
		$this->data->RemoveSerie('DEFAULT_SCALE'); // clear scale serie for setScale method
		
		// draw grid
		if ($grid) {
			$this->chart->drawGrid(3,TRUE,230,230,230,50); // drawGrid($LineWidth,$Mosaic=TRUE,$R=220,$G=220,$B=220,$Alpha=255) 
		}
		
		// draw the 0 line
		//$this->chart->setFontProperties($this->font,6);
		//$this->chart->drawTreshold(0,143,55,72,TRUE,TRUE);

		// draw the cubic curve graph
		if ($curve) {
			$this->chart->drawCubicCurve($this->data->GetData(),$this->data->GetDataDescription());
			if ($filled) {
				$this->chart->drawFilledCubicCurve($this->data->GetData(),$this->data->GetDataDescription(),0.1,50); // filled cubic curve graph
			}
		// draw the line graph
		} else {
			$this->chart->drawLineGraph($this->data->GetData(),$this->data->GetDataDescription());
			if ($filled) {
				$this->chart->drawFilledLineGraph($this->data->GetData(),$this->data->GetDataDescription(),50);
			}
		}
		
		// plot style point
		if ($plot) {
			$this->chart->drawPlotGraph($this->data->GetData(),$this->data->GetDataDescription(),3,2,255,255,255,true);  // drawPlotGraph(&$Data,&$DataDescription,$BigRadius=5,$SmallRadius=2,$R2=-1,$G2=-1,$B2=-1,$Shadow=FALSE)  
		}
		
		// add Title
		$this->chart->setFontProperties($this->font,10);
		$this->chart->drawTitle(40,0,$this->title,50,50,50,$title_w,$title_h); // drawTitle($XPos,$YPos,$Value,$R,$G,$B,$XPos2=-1,$YPos2=-1,$Shadow=FALSE)
		
		// add Legend
		$this->chart->setFontProperties($this->font,8);
		$this->chart->drawLegend($legend_w,$legend_h,$this->data->GetDataDescription(),255,255,255); // drawLegend($description,$R,$G,$B)
	}
	
	/**
	 * Draw bar style chart
	 * @return unknown
	 */
	protected function drawBar () 
	{
		// prepare font & series
		$this->_prepareSerie();
		$this->_prepareFont();
		
		// init chart params
		$outer_w	= $this->w - 5;		// Outer frame witdh
		$outer_h	= $this->h - 5;		// Outer frame heigth
		$inner_w	= $this->w - 7;		// Inner frame witdh
		$inner_h	= $this->h - 7;		// Inner frame heigth
		$chart_w	= $this->w - 150;	// Chart frame witdh
		$chart_h	= $this->h - 40;	// Chart frame heigth
		$title_w	= $this->w - 200;	// Title width
		$title_h	= 45;				// Title height
		$legend_w	= $chart_w + 30;	// Legend width
		$legend_h	= 40;				// Legend height
		
		// chart styles
		$grid	= isset($this->p['grid']) ? $this->p['grid'] : false;
		
		// fill chart
		$this->chart->drawBackground(255,255,255);
		$this->chart->setFontProperties($this->font,7); // set font and size
		$this->chart->drawRoundedRectangle(5,5,$outer_w,$outer_h,10,230,230,230); // drawRoundedRectangle($X1,$Y1,$X2,$Y2,$Radius,$R,$G,$B)  
		$this->chart->drawFilledRoundedRectangle(7,7,$inner_w,$inner_h,10,240,240,240); // drawRoundedRectangle($X1,$Y1,$X2,$Y2,$Radius,$R,$G,$B)  
		$this->chart->setGraphArea(90,40,$chart_w,$chart_h); // setGraphArea($X1,$Y1,$X2,$Y2) 
		$this->chart->drawGraphArea(255,255,255,TRUE); // drawGraphArea($R,$G,$B)
		$this->chart->drawScale($this->data->GetData(),$this->data->GetDataDescription(),SCALE_NORMAL,150,150,150,TRUE,0,2,$this->margin,$this->skip); // drawScale($Data,$DataDescription,$ScaleMode,$R,$G,$B,$DrawTicks=TRUE,$Angle=0,$Decimals=1,$WithMargin=FALSE,$SkipLabels=1,$RightScale=FALSE)  
		
		$this->data->RemoveSerie('DEFAULT_SCALE'); // clear scale serie for setScale method
		
		// draw grid
		if ($grid) {
			$this->chart->drawGrid(3,TRUE,230,230,230,50); // drawGrid($LineWidth,$Mosaic=TRUE,$R=220,$G=220,$B=220,$Alpha=255) 
		}

		// draw the cubic curve graph
		$this->chart->drawBarGraph($this->data->GetData(),$this->data->GetDataDescription(),TRUE);
		
		// add Title
		$this->chart->setFontProperties($this->font,10);
		$this->chart->drawTitle(40,0,$this->title,50,50,50,$title_w,$title_h); // drawTitle($XPos,$YPos,$Value,$R,$G,$B,$XPos2=-1,$YPos2=-1,$Shadow=FALSE)
		
		// add Legend
		$this->chart->setFontProperties($this->font,8);
		$this->chart->drawLegend($legend_w,$legend_h,$this->data->GetDataDescription(),255,255,255); // drawLegend($description,$R,$G,$B)
	}
	
	/**
	 * Draw pie chart
	 * @return unknown
	 */
	protected function drawPie () 
	{
		// prepare font & series
		$this->_prepareSerie();
		$this->_prepareFont();
		
		// init chart params
		$outer_w	= $this->w - 5;					// Outer frame witdh
		$outer_h	= $this->h - 5;					// Outer frame heigth
		$inner_w	= $this->w - 7;					// Inner frame witdh
		$inner_h	= $this->h - 7;					// Inner frame heigth
		$pie_x		= intval(($this->w - 150) / 2);	// Pie witdh
		$pie_y		= intval(($this->h - 10) / 2);	// Pie heigth
		$pie_r		= intval($pie_x - 50);			// Pie radius
		$title_w	= $this->w - 200;				// Title width
		$title_h	= 50;							// Title height
		$legend_w	= $this->w - 120;				// Legend width
		$legend_h	= 50;							// Legend height
		
		// chart styles
		$flat	= isset($this->p['flat']) ? $this->p['flat'] : false;
		
		// fill chart
		$this->chart->drawBackground(255,255,255);
		$this->chart->setFontProperties($this->font,7); // set font and size
		$this->chart->drawRoundedRectangle(5,5,$outer_w,$outer_h,10,230,230,230); // drawRoundedRectangle($X1,$Y1,$X2,$Y2,$Radius,$R,$G,$B)  
		$this->chart->drawFilledRoundedRectangle(7,7,$inner_w,$inner_h,10,240,240,240); // drawRoundedRectangle($X1,$Y1,$X2,$Y2,$Radius,$R,$G,$B)  
		
		// draw the pie chart  
		$this->chart->setFontProperties($this->font,8);
		
		// flat pie
		if ($flat) {
			$this->chart->drawFlatPieGraphWithShadow($this->data->GetData(),$this->data->GetDataDescription(),$pie_x,$pie_y,$pie_r,PIE_PERCENTAGE,10);
		// 3d pie
		} else {
			$this->chart->drawPieGraph($this->data->GetData(),$this->data->GetDataDescription(),$pie_x,$pie_y,$pie_r,PIE_PERCENTAGE,TRUE,50,20,5,1);  
		}
		
		$this->chart->drawPieLegend($legend_w,$legend_h,$this->data->GetData(),$this->data->GetDataDescription(),250,250,250);
		
		// add title
		$this->chart->setFontProperties($this->font,10);
		$this->chart->drawTitle(40,0,$this->title,50,50,50,$title_w,$title_h); // drawTitle($XPos,$YPos,$Value,$R,$G,$B,$XPos2=-1,$YPos2=-1,$Shadow=FALSE)
	}
	
	/**
	 * Get font through file path
	 * @access private
	 */
	private function _prepareFont ()
	{
		$this->font = __FONT_DIR . DIRECTORY_SEPARATOR . $this->fontn;
	}
	
	/**
	 * Prepare specific serie for the chart
	 * @access private
	 */
	private function _prepareSerie ()
	{
		$this->data->AddAllSeries();
		
		if ($this->label) {
			$this->data->SetAbsciseLabelSerie($this->label);
		} else {
			$this->data->SetAbsciseLabelSerie();
		}
	}
	
	/**
	 * Check if serie exists
	 * @param string $serie
	 * @access private
	 */
	public function _checkSerieExists ($serie)
	{
		if (!$serie && !in_array($serie, $this->serie)) {
			require_once 'Hush/Chart/Exception.php';
			throw new Hush_Chart_Exception('Non-exists serie \'' . $serie . '\', please add series first');
			return false;
		}
		return true;
	}
}
?>