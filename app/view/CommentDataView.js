Ext.define('FreescribbleApp.view.CommentDataView', {
    extend: 'Ext.dataview.DataView',
    xtype: 'commentdataview',
    config: {
        itemId: 'commentDataViewId',
        scrollable: null,
        defaultType: 'commentitem',
        useComponents: true
    },
});
