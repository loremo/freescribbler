Ext.define('FreescribbleApp.view.Home2', {
    extend: 'Ext.Panel',
    xtype: 'homepage2',
    config: {
        styleHtmlContent: true,
        scrollable: true,
        autoScroll: true,
        items:[
            {
                xtype: 'titlebarview'
            },
            {
                xtype: 'userinfoview',
                itemId: 'userInfoWrapper',
            },
            {
                itemId: 'userContent',
            }
        ]
    }
});
