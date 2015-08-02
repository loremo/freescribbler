Ext.define('FreescribbleApp.view.PostPage', {
    extend: 'Ext.Panel',
    xtype: 'postpage',
    config: {
        styleHtmlContent: true,
        scrollable: true,
        items:[
            {
                xtype: 'titlebarview'
            },
            {
                xtype: 'onepost',
                itemId: 'postContentBlock',
            },
            {
                itemId: 'moreCommentsBtn',
                itemCls: 'moreCommentsCls',
                html: 'предыдущие комментарии'
            },
            {
                itemId: 'commentBlock',
            }
        ] 
    }
});
