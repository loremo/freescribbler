Ext.define('FreescribbleApp.view.UserLightDataView', {
    extend: 'Ext.dataview.DataView',
    xtype: 'homedataview',
    config: {
        itemId: 'homeDataViewId',
        scrollable: null,
        cls: 'userLightWrapper',
        defaultType: 'userlightitem',
        useComponents: true
    },
});
