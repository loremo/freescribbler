Ext.define('FreescribbleApp.store.PicsStore', {
    extend: 'Ext.data.Store',
    requires: 'FreescribbleApp.model.PostModel',
    config: {
    	storeId: 'pics',
        model: 'FreescribbleApp.model.PicModel',
        params: {
            offsetPostId: 999999999,
            postsLimit: 20
        }/*,
        proxy: {
            type: 'ajax',
            actionMethods: {
                create : 'POST',
                read   : 'POST', // by default GET
                update : 'POST',
                destroy: 'POST'
            },
            api: {
                read: 'mockdata/posts.php',
                destroy: 'mockdata/deletePost.php',
                update: 'mockdata/updatePostPoints.php',
                create: '/some/url/to/update/records/in/db2',
            },
            reader: {
                type: 'json',
                rootProperty: 'posts'
            },
            writer: {
                type: 'json',
                encode: true
            }
        }*/,
        
        listeners : {
            load : function(store) {
                console.log('get PICS');
            }
        }
    }
});