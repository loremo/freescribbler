Ext.define('FreescribbleApp.view.PostDataView', {
    extend: 'Ext.dataview.DataView',
    xtype: 'homedataview',
    config: {
        itemId: 'homeDataViewId',
        scrollable: null,
        defaultType: 'postitem',
        useComponents: true
    },
});
