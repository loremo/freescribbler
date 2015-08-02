Ext.define('FreescribbleApp.store.EventStore', {
    extend: 'Ext.data.Store',
    requires: 'FreescribbleApp.model.EventModel',
    config: {
    	model: 'FreescribbleApp.model.EventModel',
        proxy: {
            type: 'ajax',
            actionMethods: {
                create : 'POST',
                read   : 'POST', // by default GET
                update : 'POST',
                destroy: 'POST'
            },
            api: {
                read: 'http://freescribbler.com/test.php'
            },
            reader: {
                type: 'json',
                rootProperty: 'data',
                successProperty: 'success'
            },
            writer: {
                type: 'json',
                encode: true
            }
        },
        
        listeners : {
            load : function(store) {
                console.log('get Home posts');
                var rawData = store.getProxy().getReader().rawData;
                this.getProxy().setExtraParams({
                    offset: rawData.lastevent
                });
            }
        }
    }
});