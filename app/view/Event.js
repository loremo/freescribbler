Ext.define('FreescribbleApp.view.event', {
    extend: 'Ext.Panel',
    xtype: 'eventpage',
    config: {
        styleHtmlContent: true,
        scrollable: true,
        title: 'События',
        iconCls: 'event',
        items:[
            {
                itemId: 'eventContent',
            }
        ]
    }
});
