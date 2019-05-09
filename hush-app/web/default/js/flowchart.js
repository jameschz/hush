/*!
 * Flow chart javascript library for pbpm
 * Copyright 2011, James.huang <shagoo@gmail.com>
 */
var FlowChart = 
{
	canvas : null,
	
	initCanvas : function (canvasId)
	{
		this.canvas = document.getElementById(canvasId);
		if (!this.canvas.getContext) alert('请检查你的浏览器是否支持 Canvas 控件.');
	},
	
	drawLine : function (e1, e2)
	{
		try {
		
			e1 = $('#' + e1);
			e2 = $('#' + e2);
			w1 = parseInt(e1.width(), 10);
			w2 = parseInt(e2.width(), 10);
			h1 = parseInt(e1.height(), 10);
			h2 = parseInt(e2.height(), 10);
			l1 = parseInt(e1.position().left, 10);
			l2 = parseInt(e2.position().left, 10);
			t1 = parseInt(e1.position().top, 10);
			t2 = parseInt(e2.position().top, 10);
			
			sPos = { 'x' : l1 + w1 / 2, 'y' : t1 + h1 / 2 }
			ePos = { 'x' : l2 + w2 / 2, 'y' : t2 + h2 / 2 }
			
			var ctx = this.canvas.getContext('2d');
			ctx.lineWidth = 1;
			ctx.beginPath();
			ctx.moveTo(sPos.x, sPos.y);
			ctx.lineTo(ePos.x, ePos.y);
			ctx.closePath();
			ctx.strokeStyle = "#66CC00";
			ctx.stroke();
			
			this.drawArrow(sPos, ePos);
			
		} catch (e) {}
	},
	
	drawArrow : function (p1, p2)
	{
		la = 10;
		lx = p2.x - p1.x;
		ly = p2.y - p1.y;
		lz = Math.sqrt(Math.pow(lx, 2) + Math.pow(ly, 2));
		xb = p1.x + (lx / 2);	// 中间点坐标 x
		yb = p1.y + (ly / 2);	// 中间点坐标 y
		xa = la * (lx / lz);	// 箭头三角形关键长度 x
		ya = la * (ly / lz);	// 箭头三角形关键长度 y
		x1 = xb + xa;			// 箭头顶点坐标 x
		y1 = yb + ya;			// 箭头顶点坐标 y
		x2 = xb + (ya / 2);		// 箭头边点坐标 x
		y2 = yb - (xa / 2);		// 箭头边点坐标 y
		x3 = xb - (ya / 2);		// 箭头边点坐标 x
		y3 = yb + (xa / 2);		// 箭头边点坐标 y
		
		var ctx = this.canvas.getContext('2d');
		ctx.beginPath();
		ctx.moveTo(x1, y1);
		ctx.lineTo(x2, y2);
		ctx.lineTo(x3, y3);
		ctx.closePath();
		ctx.fillStyle = "#66CC00";
		ctx.fill();
	},
	
	clearAll : function ()
	{
		var ctx = this.canvas.getContext('2d');
		ctx.clearRect(0,0,800,600);
	},
	
	debug : function (msg)
	{
		if (!$('#flowchartdebug').length) {
			$('<div id="flowchartdebug"></div>').css({
				'width' : 200,
				'height' : 200,
				'background' : 'white',
				'border' : '1px solid red',
				'position' : 'absolute',
				'right' : 0,
				'top' : 0
			}).appendTo('body');
		}
		$('#flowchartdebug').html(msg);
	}
}