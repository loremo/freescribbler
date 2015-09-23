//<debug>
Ext.Loader.setPath({
    'Ext': 'sdk/src',
	'Ext.ux': 'libs/ux'    
});
//</debug>

Ext.application({
    name: 'PinchZoomImage',

    requires: [
        'Ext.MessageBox'
    ],

    views: ['Main'],

    icon: {
        57: 'resources/icons/Icon.png',
        72: 'resources/icons/Icon~ipad.png',
        114: 'resources/icons/Icon@2x.png',
        144: 'resources/icons/Icon~ipad@2x.png'
    },
    
    phoneStartupScreen: 'resources/loading/Homescreen.jpg',
    tabletStartupScreen: 'resources/loading/Homescreen~ipad.jpg',

    launch: function() {
        Ext.fly('appLoadingIndicator').destroy();
        Ext.Viewport.add(Ext.create('PinchZoomImage.view.Main'));
        
        // redraw image when orientation is changed. 
        Ext.getDom('ext-viewport').addEventListener('orientationchange', function() {
        	var pinchzooms = Ext.ComponentQuery.query('pinchzoomimage');
        	for (var i=0;i<pinchzooms.length;i++) {
        		pinchzooms[i].redraw();
        	}
        });
    },

    onUpdated: function() {
        Ext.Msg.confirm(
            "Application Update",
            "This application has just successfully been updated to the latest version. Reload now?",
            function() {
                window.location.reload();
            }
        );
    }
});
