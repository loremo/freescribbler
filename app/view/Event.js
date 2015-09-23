Ext.define('FreescribbleApp.view.Event', {
    extend: 'Ext.Panel',
    xtype: 'eventpage',
    config: {
        styleHtmlContent: true,
        scrollable: true,
        title: 'События',
        iconCls: 'event',
        cls: 'eventWrapper',
        items:[
            {
                itemId: 'eventContent',
            }
        ]
    }
});
