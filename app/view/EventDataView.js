Ext.define('FreescribbleApp.view.EventDataView', {
    extend: 'Ext.dataview.DataView',
    xtype: 'eventdataview',
    config: {
        itemId: 'eventDataViewId',
        scrollable: null,
        defaultType: 'eventitem',
        useComponents: true
    },
});
