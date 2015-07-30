Ext.define('FreescribbleApp.store.CommentStore', {
    extend: 'Ext.data.Store',
    requires: 'FreescribbleApp.model.CommentModel',
    config: {
    	storeId: 'commentstore',
        model: 'FreescribbleApp.model.CommentModel',
        params: {
            limit: 10
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
                destroy: 'mockdata/deleteComment.php',
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
                    offset: rawData.lastcomment,
                    limit: 30
                });
            }
        }
    }
});