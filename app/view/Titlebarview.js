Ext.define('FreescribbleApp.view.Titlebarview', {
    extend: 'Ext.TitleBar',
    requires: ['Ext.ux.menu.Menu'],
    xtype: 'titlebarview',
    config: {
        docked: 'top',
        title: 'Freescribble.com',
        items: [
            {
                xtype: 'button',
                iconCls: 'settings',
                align: 'left',
                itemId: 'menuButton',
            },
        ],
        listeners: [
            {
                delegate: '#menuButton',
                event: 'tap',
                fn: 'onMenuButtonTap'
            }
        ]
    },
    onLogOutButtonTap: function () {
        var me = this;
        me.fireEvent('signOutCommand', me);
    },
    onMenuButtonTap: function () {
        var me = this,
            button = Ext.getCmp('ext-button-1');
        Ext.ux.menu.Menu.open(
            button, // the anchor
            [
                { 
                    text: 'Настройки', 
                    value: 'settings' 
                },
                { 
                    text: 'Выход', 
                    value: 'logout' 
                }
            ],
            function(value) { 
                // callback (called after the menu is closed)
                // The value will be 'value1', 'value2', or 'value3'.
                // If you close the menu by tapping on the mask, it becomes null.
                if (value=='settings') {
                    console.log('settings')
                }
                if (value=='logout') {
                     me.fireEvent('signOutCommand', me);
                }
            }
        );
    }
    
});
