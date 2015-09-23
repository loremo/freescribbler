/**
 * Pinch Zoom Image for Sencha Touch 2
 * 
 * requieres the Transform.js file for the transformation of canvas
 *
 * @author Sang Oh <swoh77@gmail.com>
 * 
 * This source is orignated from Zooming via HTML5 Canvas Context and restructured into sencha touch.
 * Origin : http://phrogz.net/tmp/canvas_zoom_to_cursor.html
 *          author Gavin Kistner < mailto:!@phrogz.net >
 * 
 */

Ext.define('Ext.ux.PinchZoomImage', {
	extend:'Ext.Container',
	xtype:'pinchzoomimage',
	requires:['Ext.ux.Transform'],
	config:{
		src:null,			// source of image to draw
		isFrozen:false,		// default false, when set true, transformation is disabled.
		scaleFactor:1.1,	//  default 1.1	,control the scale ratio,
		useMouseWheelEvent:true,	// default: true, when set true, scaling is applied with mouse wheel event
		
		styleHtmlContent:true,
		layout:'fit',
		html:'<canvas style="position:absolute;top:0px;left:0px;width:100%;height:100%;"/>',
		listeners:{
			scope:this,	
			painted:function(cmp) {
				//console.log("pinchzoomimage:painted", this);
				cmp.redraw();
			},
			doubletap: {
				fn : function(e, el, obj) {
					//console.log("pinchzoomimage:doubletap", this);
					if (this.getIsFrozen()) return;
						this.redraw();
				},	
				element: 'element',
				delegate:'canvas'				
			},
			dragstart: {
				fn: function(e, el, obj) {
					if (this.getIsFrozen()) return;
					this.lastX = e.pageX;
					this.lastY = e.pageY;
					document.body.style.mozUserSelect = document.body.style.webkitUserSelect = document.body.style.userSelect = 'none';
				
					this.dragStart = this.ctx.transformedPoint(this.lastX,this.lastY);
					this.dragged = false;
				},	
				element: 'element',
				delegate:'canvas'		
			},
			dragend: {
				fn :function(e, el, obj) {
					this.dragged = false;
					if (this.getIsFrozen()) return;
					this.lastX = e.pageX;
					this.lastY = e.pageY;
					this.dragStart = null;   
				},	
				element: 'element',
				delegate:'canvas'			
			},
			drag: {
				fn :function(e, el, obj) {
					if (this.getIsFrozen()) return;
					this.lastX = e.pageX;
					this.lastY = e.pageY;
					
					if (this.dragStart && this.isScaled){
						var pt = this.ctx.transformedPoint(this.lastX, this.lastY);
						this.ctx.translate(pt.x-this.dragStart.x,pt.y-this.dragStart.y);
						this.draw();
					}			
					this.lastX = e.offsetX || (e.pageX - this.canvas.offsetLeft);
					this.lastY = e.offsetY || (e.pageY - this.canvas.offsetTop);
				},	
				element: 'element',
				delegate:'canvas'		
			},
			pinchstart: {
				fn :function (e, el, obj) {
					if (this.getIsFrozen()) return;
					this.lastX = e.pageX;
					this.lastY = e.pageY;
					this.dragged = false;
				},	
				element: 'element',
				delegate:'canvas'			
			},
			pinchend: {
				fn : function (e, el, obj) {
					if (this.getIsFrozen()) return;
					this.lastX = e.pageX;
					this.lastY = e.pageY;
					dragged = false;
				},	
				element: 'element',
				delegate:'canvas'					
			},	
			pinch : {
				fn : function (e, el, obj) {	
					if (this.getIsFrozen()) return;
					this.zoom(e.scale-1);
					this.dragged = true;		
				},	
				element: 'element',
				delegate:'canvas'			
			},		
		}			
	},

	/********private variable****/
	lastX:0,
	lastY:0,
	image:null,
	canvas:null,	
	ctx:null,
	srcWidth:null,
	srcHeight:null,
	destWidth:null,
	destHeight:null,	
	ratioWidth:null,
	ratioHeight:null,
	canvasWidth:null,
	canvasHeight:null,
	isScaled:false,
	/*********************/
	
	/**
	 * [applySrc setter for image ]
	 * @param  {string} value [source of image] 
	 */		
	 applySrc:function(value) {
		//console.log("pinchzoomimage:applySrc",value)
		if (value != null && value != "") {
			var img = this.getImage();
			img.src = value;
		}
		return value;
	},
	/**
	 * [getImage create and get image buffer]
	 */		
	 getImage:function() {
		if (this.image == null) {
			this.image = new Image();
			
			this.image.onload = (function(m){ 
			    return function(){
			        m.onLoadImage();
			    }
			})(this);
			this.image.onerror=this.onErrorImage;
			this.image.onabor=this.onAbortImage;
		}
		
		return this.image;
	},
	/**
	 * [resetCanvas initialize the canvas size and transformation]
	 */		
	 resetCanvas:function() {
		if (this.canvas != null) {
			this.isScaled = false;
			this.zoomValue=0;
			this.ctx.reset();
			this.canvas.width = this.element.getWidth();
			this.canvas.height = this.element.getHeight();
				
			this.lastX=this.canvasWidth/2;
			this.lastY=this.canvasHeight/2;	
		}
	},
	/**
	 * [handleScroll scroll zoom event handler]
	 * @param  {[type]} evt [event argument]
	 */	
	 handleScroll:function(evt){
		//console.log("handleScroll");
		if (!this.getUseMouseWheelEvent()) return;
		var delta = evt.wheelDelta ? evt.wheelDelta/40 : evt.detail ? -evt.detail : 0;
		if (delta) 	this.zoom(delta);
		return evt.preventDefault() && false;
	},
	/**
	 * [trackTransforms allocate canvas transformer]
	 * @param  {[type]} ctx [2d context of canvas]
	 */		
	trackTransforms:function(ctx){
			var xform = Ext.create('Ext.ux.Transform');
			ctx.getTransform = function(){ return xform; };
			
			var savedTransforms = [];
	
			var save = ctx.save;
			ctx.save = function(){
				savedTransforms.push(xform.translate(0,0));
				return save.call(ctx);
			};
			var restore = ctx.restore;
			ctx.restore = function(){
				xform = savedTransforms.pop();
				return restore.call(ctx);
			};		
			
			var translate = ctx.translate;
			ctx.translate = function(dx,dy){
			  xform.translate(dx,dy);
				return translate.call(ctx,dx,dy);
			};
			ctx.reset = function(dx,dy){
			  xform.reset();
			};
			
			var scale = ctx.scale;
				ctx.scale = function(sx,sy){
				xform.scale(sx,sy);
				return scale.call(ctx,sx,sy);
			};
			
			var pt  = {x:0, y:0};
			var setTransform = ctx.setTransform;
			ctx.transformedPoint = function(x,y){
				var xform2 = xform.clone();
				xform2.invert();
				var pt2 = xform2.transformPoint(x, y);
				
				var newPt = {x:pt2[0], y:pt2[1]};
				return newPt;
			}
	},
	/**
	 * [zoom scale the image from the point(lastX,lastY).]
	 * @param  {[type]} zValue [scaling value]
	 */	
	zoom:function(zValue){
		if (this.zoomValue+ zValue < 0) return;	// prohibit to downsize less than fitted image.
		this.isScaled = true;
		this.zoomValue += zValue;		
		var pt = this.ctx.transformedPoint(this.lastX, this.lastY);
		this.ctx.translate(pt.x,pt.y);
		var factor = Math.pow(this.getScaleFactor(),zValue/2);
		this.ctx.scale(factor,factor);
		this.ctx.translate(-pt.x,-pt.y);
		this.draw();
	},
	/**
	 * [redraw redraw image after reset the transformation of canvas]
	 */		
	 redraw:function() {
		this.resetCanvas();
		this.draw();
	},
	/**
	 * [draw draw image on the canvas size with resized from original.]
	 */	
	draw:function (){
		if (this.image.complete  == true && this.ctx != null) {	
			var p1 = this.ctx.transformedPoint(0,0);
			var p2 = this.ctx.transformedPoint(this.canvas.width,this.canvas.height);
			this.ctx.clearRect(p1.x,p1.y,p2.x-p1.x,p2.y-p1.y);
			this.srcWidth = this.image.width;
			this.srcHeight = this.image.height;
		
			this.destWidth = this.canvas.width;
			this.destHeight = this.canvas.height;
						
			this.ratioWidth = this.srcWidth/this.destWidth;
			this.ratioHeight = this.srcHeight/this.destHeight;
	
			if (this.ratioWidth > this.ratioHeight) {
				this.destHeight = this.srcHeight/this.ratioWidth;
			} else {
				this.destWidth = this.srcWidth/this.ratioHeight;
			}
	
			this.srcX = 0;
			this.srcY = 0;
		
			this.destX = (this.canvas.width - this.destWidth)/2;
			this.destY = (this.canvas.height - this.destHeight)/2;
						
			this.ctx.drawImage(this.image, this.srcX, this.srcY, this.srcWidth, this.srcHeight, this.destX, this.destY, this.destWidth, this.destHeight);
		}
	},
	/**
	 * [onLoadImage image load hadler to draw it on the canvas]
	 */	
	onLoadImage:function() {
		//console.log("pinchzoomimage:onLoadImage to load image");
		this.initCanvas();
		this.redraw();
	},
	onErrorImage:function() {
		//console.log("pinchzoomimage:onErrorImage to load image");
	},
	onAbortImage:function() {
		//console.log("pinchzoomimage:onAbortImage to load image");
	},
	/**
	 * [initCanvas image load hadler to draw it on the canvas]
	 */		
	 initCanvas:function() {
		this.canvas = Ext.DomQuery.select("canvas", this.element.dom)[0];
		this.ctx = this.canvas.getContext('2d');
		this.trackTransforms(this.ctx);
		this.canvas.addEventListener('mousewheel',	
					(function(m){ 
					    return function(evt){
					        m.handleScroll(evt);
					    }
					})(this),
					false);
		this.resetCanvas();	
		//console.log("pinchzoomimage:initCanvas",this.canvas)
	}
});
