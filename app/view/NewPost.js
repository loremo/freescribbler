Ext.define('FreescribbleApp.view.NewPost', {
    extend: 'Ext.Panel',
    xtype: 'newpost',
    config: {
        title: 'Написать',
        iconCls: 'input',
        styleHtmlContent: true,
        layout: {
            type: 'card',
            animation: 'flip'
        },
        items: [
            {
                xtype: 'inputpage',
                itemId: 'inputPageWrapper'
            },
            {
                xtype: 'picprocessing',
                itemId: 'picProcessingWrapper'
            }
        ]
    },
    
});
