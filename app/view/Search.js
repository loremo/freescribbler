Ext.define('FreescribbleApp.view.Search', {
    extend: 'Ext.Panel',
    xtype: 'searchpage',
    config: {
        styleHtmlContent: true,
        scrollable: true,
        title: 'Поиск',
        iconCls: 'search',
        items:[
            {
                xtype: 'button',
                itemId: 'postSearchBtn',
                html: 'Тэги'                
            },
            {
                xtype: 'button',
                itemId: 'userSearchBtn',
                html: 'Пользователи'
            },
            {
                xtype: 'fieldset',
                items: [
                    {
                        xtype: 'textfield',
                        placeHolder: 'введите хэштэг',
                        itemId: 'searchField',
                    },
                ],
            },
            {
                xtype: 'button',
                itemId: 'searchStartBtn',
                html: 'Искать'
            },
            {
                itemId: 'searchContent',
            }
        ]
    }
});
