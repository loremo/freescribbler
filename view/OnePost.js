Ext.define('FreescribbleApp.view.OnePost', {
    extend: 'Ext.Panel',
    xtype: 'onepost',
    requires: ['Ext.field.Text', 'Ext.Img', 'Ext.Container', 'Ext.Button', 'Ext.Label'],
    config: {
        title: 'Post',
        styleHtmlContent: false,
        cls: 'postWrapper',
        items: [
            {
                xtype: 'image',
                itemId: 'posterAvatar',
                cls: 'postAvatar'
            },
            {
                xtype: 'button',
                itemId: 'posterName',
                cls: 'posterName',
                ui: 'plain'
            },
            {
                itemId: 'postTime',
                cls: 'postTime'
            },
            {
                itemId: 'postEditBtn',
                cls: 'postEditBtn'
            },
            {
                itemId: 'postPics',
                cls: 'postPics'
            },
            {
                itemId: 'postContent',
                cls: 'postContent'
            },
            {
                itemId: 'postSeparator',
                cls: 'postSeparator'
            },
            {
                itemId: 'postLikeBtn',
                cls: 'postLikeBtn'
            },
            {
                itemId: 'postComment',
                cls: 'postComment'
            }
        ]
    }
});
