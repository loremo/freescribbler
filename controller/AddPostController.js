Ext.define("FreescribbleApp.controller.AddPostController", {
    extend: "Ext.app.Controller",
    
    config: {
        refs: {
            addPostPage: 'addpostpage',
            main: 'main',
            backBtn: '[itemId = newPostBackBtn]'
        },
        
        control: {
        	addPostPage: {
        		activate: 'onActivated'
            },
            backBtn: {
            	tap: 'backBtnTaped'
            }
        }
    },
    
    init: function () {
    	 },
    	 
    launch: function () {
    	 },
    
    backBtnTaped: function (){
    	console.log('backBtnTaped');
    	 Ext.Viewport.animateActiveItem(this.getMain(), {type: 'slide', direction: 'left', duration: 400}) ;
    	 },
    	 
    onActivated: function () {
	     },
    
   

   
});