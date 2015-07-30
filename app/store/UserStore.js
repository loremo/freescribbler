Ext.define('FreescribbleApp.store.UserStore', {
    extend: 'Ext.data.Store',
    requires: 'FreescribbleApp.model.UserModel',
    config: {
    	model: 'FreescribbleApp.model.UserModel',
        proxy: {
            type: 'ajax',
            api: {
                read: 'http://freescribbler.com/test.php',
                update: 'http://freescribbler.com/test.php',
                destroy: 'mockdata/deleteUser.php',
                create: 'mockdata/createUser.php',
            },
            actionMethods: {
                create : 'POST',
                read   : 'POST', // by default GET
                update : 'POST',
                destroy: 'POST'
            },
            reader: {
                type: 'json',
                rootProperty: 'data',
                successProperty: 'success'
            },
        },
        listeners : {
            load : function(store) {
                console.log('get User Bio');
            }
        }
    }
});