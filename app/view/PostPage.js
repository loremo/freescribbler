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
                xtype: "textareafield",
                itemId: 'commentInputField',
                placeholder: 'Напиши комментарий...',
            },
            {
                xtype: 'button',
                text: 'Отправить',
                itemId: 'submitCommentBtn',
                ui: 'confirm'
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
