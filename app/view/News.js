Ext.define('FreescribbleApp.view.News', {
    extend: 'Ext.dataview.DataView',
    xtype: 'newspageview',
    config: {
        title: 'Новости',
        iconCls: 'news',
        styleHtmlContent: true,
        itemId: 'newsDataViewId',
        defaultType: 'postitem',
        useComponents: true
    }
});
