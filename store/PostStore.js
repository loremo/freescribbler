Ext.define('FreescribbleApp.store.PostStore', {
    extend: 'Ext.data.Store',
    requires: 'FreescribbleApp.model.PostModel',
    config: {
    	storeId: 'poststore',
        model: 'FreescribbleApp.model.PostModel',
        params: {
            limit: 20
        },
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
                destroy: 'mockdata/deletePost.php',
                update: 'mockdata/updatePostPoints.php',
                create: '/some/url/to/update/records/in/db2',
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
                    offset: rawData.lastpost
                });
            }
        }
    }
});