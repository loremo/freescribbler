Ext.define('FreescribbleApp.view.News', {
    extend: 'Ext.Panel',
    xtype: 'newspage',
    config: {
        styleHtmlContent: true,
        scrollable: true,
        title: 'Новости',
        iconCls: 'news',
        items:[
            {
                itemId: 'newsContent',
            }
        ]
    }
});
