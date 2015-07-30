Ext.define('FreescribbleApp.view.Home', {
    extend: 'Ext.Panel',
    xtype: 'homepage',
    config: {
        title: 'Home',
        iconCls: 'home',
        styleHtmlContent: true,
        layout: {
            type: 'card',
            animation: 'flip'
        },
        items: [
            {
                xtype: 'homepage2',
                itemId: 'homePageWrapper'
            },
            {
                xtype: 'postpage',
                itemId: 'postPageWrapper'
            }
        ]
    }
});
