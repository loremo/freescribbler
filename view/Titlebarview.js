Ext.define('FreescribbleApp.view.Titlebarview', {
    extend: 'Ext.TitleBar',
    xtype: 'titlebarview',
    config: {
        docked: 'top',
        title: 'Freescribble.com',
        items: [
            {
                //xtype:'optionspage',
                title: 'Options',
                iconCls: 'settings',
                align: 'left',
                ui: 'plain',
                itemId: 'optionsBtn'
            },
            {
                align: 'left',
                itemId: 'logOutButton',
                xtype: 'button',
                ui: 'action',
                padding: '10px',
                margin:'10px', 
                text: 'Log Out'
            },
            {
               //xtype:'optionspage',
                title: 'Search',
                iconCls: 'search',
                align: 'right',
                ui: 'plain', 
                itemId: 'searchBtn'
            },
        ],
        listeners: [{
            delegate: '#logOutButton',
            event: 'tap',
            fn: 'onLogOutButtonTap'
        }]
    },
    onLogOutButtonTap: function () {
        var me = this;
        me.fireEvent('signOutCommand', me);
    }
    
});
