Ext.define('FreescribbleApp.store.UserLightStore', {
    extend: 'Ext.data.Store',
    requires: 'FreescribbleApp.model.UserLightModel',
    config: {
        storeId: 'userfriendsstore',
    	model: 'FreescribbleApp.model.UserLightModel',
        proxy: {
            type: 'ajax',
            actionMethods: {
                create : 'POST',
                read   : 'POST', // by default GET
                update : 'POST',
                destroy: 'POST'
            },
            api: {
                read: 'http://freescribbler.com/test.php',
                update: 'http://freescribbler.com/test.php',
                destroy: 'mockdata/deleteUser.php',
                create: 'mockdata/createUser.php',
            },
            reader: {
                type: 'json',
                rootProperty: 'data'
            },
            writer: {
                type: 'json',
                encode: true
            }
        },
        
        listeners : {
            load : function(store) {
                console.log('get User List' + store.getProxy().getReader());
                var rawData = store.getProxy().getReader().rawData;
                this.getProxy().setExtraParams({
                    offset: rawData.lastuser
                });
            }
        }
    }
});