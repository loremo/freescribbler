Ext.define("PinchZoomImage.view.Main", {
    extend: 'Ext.tab.Panel',
    requires: ['Ext.TitleBar','Ext.ux.PinchZoomImage'],   
    config: {
        tabBarPosition: 'bottom',
        items: [
            {
                title: 'Single',
                iconCls: 'home',
                layout:'fit',
                items: [{
                    docked: 'top',
                    xtype: 'titlebar',
                    title: 'Single View'
                },
                {
                layout:'fit',
                	xtype:'pinchzoomimage',
	            	style:'background-color:#000',
	            	src:'resources/images/mybike.jpg',
	            }   
                ]
            },
            {
                title: 'Multi',
                iconCls: 'home',
				layout:'vbox',
		    	defaults:{
					flex:1,
					layout:'fit',
				},     
		        items: [
	     		{
                    docked: 'top',
                    xtype: 'titlebar',
                    title: 'Multi View'
	            },{
	            	xtype:'container',
	            	items:{
						id:"p",
						xtype:'pinchzoomimage',
		            	style:'background-color:#000',
		            	src:'resources/images/mybike.jpg'            		
	            	}
	            },{
	            	xtype:'container',
	            	
	            	items:{
						id:"q",
					    xtype:'pinchzoomimage',
		            	style:'background-color:#000',
		            	src:'resources/images/mybike.jpg'            		
	            	}
	            	
	            }
		        ]
			}
        ]
    }
});