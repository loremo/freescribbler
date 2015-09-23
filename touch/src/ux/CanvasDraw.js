Ext.define('Ext.ux.CanvasDraw', {
    extend: 'Ext.Component',
    xtype: 'canvasdraw',
 
    template: [{
        reference: 'canvas',
        tag: 'canvas',
        classList: [Ext.baseCSSPrefix + 'canvas']
    }],
 
    config: {
 
        /**
        * @cfg brushColor
        * The color that will be used for drawing
        * @accessor
        */
        brushColor: '#000',
 
        /**
        * @cfg brushSize
        * Used to determine line width, which will
        * control the thickness of the "brush"
        * @accessor
        */
        brushSize: 1,
 
        /**
        * @cfg brushType
        * The type of brush to be used
        * can be set to 'eraser' or 'normal'
        * @accessor
        */
        brushType: 'normal',
        cX: 1,
        cY: 1,
        cWidth: 1,
        cHeight: 1
    },
 
    initialize: function(){
 
        var me = this;
        me.callParent();
 
        me.on('painted', 'initCanvas', me);
 
        me.element.on({
            touchstart: 'onTouchStart',
            touchend: 'onTouchEnd',
            touchmove: 'onTouchMove',
            scope: me
        });
 
    },
 
    initCanvas: function(){
 
        var me = this,
            canvas = me.element.dom.firstChild;
 
        me.painting = false;
        me.iFirstX = 1;
        me.iFirstY = 1;
        me.iLastX = 200;
        me.iLastY = 200;
        me.setSize();
 
        if(canvas){
            me.canvas = canvas;
            canvas.setAttribute("width", me.width);
            canvas.setAttribute("height", me.height);
            me.context = canvas.getContext("2d");
            me.context.strokeStyle = me.getBrushColor();
            me.context.lineWidth = me.getBrushSize();
            me.context.strokeRect(me.iFirstX, me.iFirstY, me.iLastX-me.iFirstX, me.iLastY-me.iFirstY);
        }
    },
 
    setSize: function(width, height){
 
        var me = this;
        if(!width){
            if (!me.width) {
                var totalOffsetX = 0;
                var totalOffsetY = 0;
                var canvasX = 0;
                var canvasY = 0;
                var currentElement = me.element.dom.firstChild;

                do {
                    totalOffsetX += currentElement.offsetLeft;
                    totalOffsetY += currentElement.offsetTop;
                }
                while(currentElement = currentElement.offsetParent);

                me.width = window.innerWidth - totalOffsetX;
                me.height = window.innerHeight - totalOffsetY;
            }
        }
        else {
            me.width = width;
            me.height = height;
        }
        
        var canvas = me.element.dom.firstChild;
        canvas.setAttribute("width", me.width);
        canvas.setAttribute("height", me.height);
    },
 
    relMouseCoords: function(e){
        var me = this;
        var totalOffsetX = 0;
        var totalOffsetY = 0;
        var canvasX = 0;
        var canvasY = 0;
        var currentElement = me.element.dom.firstChild;
 
        do {
            totalOffsetX += currentElement.offsetLeft;
            totalOffsetY += currentElement.offsetTop;
        }
        while(currentElement = currentElement.offsetParent);
 
        canvasX = e.pageX - totalOffsetX;
        canvasY = e.pageY - totalOffsetY;
 
        return {x:canvasX, y:canvasY};
    },
 
    onTouchStart: function(e) {
 
        var me = this,
            coords = me.relMouseCoords(e);
 
        me.painting = true;
        me.iFirstX = coords.x;
        me.iFirstY = coords.y;        
        me.iLastX = -1;
        me.iLastY = -1;
    },
 
    onTouchEnd: function(e) {
        var me = this;
        me.setCX(Math.min(me.iFirstX,me.iLastX));
        me.setCY(Math.min(me.iFirstY,me.iLastY));
        me.setCWidth(Math.abs(me.iLastX - me.iFirstX));
        me.setCHeight(Math.abs(me.iLastY - me.iFirstY));
        me.paining = false;
    },
 
    onTouchMove: function(e) {
 
        var me = this;
 
        if(me.painting){
 
            var coords = me.relMouseCoords(e),
                    iX = coords.x, iY = coords.y;
            if (coords.x < 1) iX = 1;
            if (coords.x > me.width) iX = (me.width - 1);
            if (coords.y < 1) iY = 1;
            if (coords.y > me.height) iY = (me.height - 1);
            
            me.iLastX = iX;
            me.iLastY = iY;
            
            me.resetCanvas();
            me.context.strokeRect(me.iFirstX, me.iFirstY, me.iLastX-me.iFirstX, me.iLastY-me.iFirstY);
        }
    },
 
    resetCanvas: function(){
        var me = this,
            canvas = me.element.dom.firstChild,
            context = canvas.getContext("2d");
 
        context.clearRect(0,0,canvas.width,canvas.height);
    },
 
    saveCanvas: function(){
        var me = this,
            canvas = me.element.dom.firstChild;
 
        if(window.canvas2ImagePlugin){
            var canvas2ImagePlugin = window.canvas2ImagePlugin;
 
            canvas2ImagePlugin.saveImageDataToLibrary(function(msg){
                console.log(msg);
            },
            function(err){
                console.log(err);
            }, canvas);
        }
    }
 
});