Ext.define('FreescribbleApp.model.PostModel', {
    extend: 'Ext.data.Model',
    config: {
        idProperty: 'postid',
    	fields: [
            {name: 'useravatar', type: 'string'},
            {name: 'userid', type: 'number'},
            {name: 'username', type: 'string'},
            {name: 'postid', type: 'number'},
            {name: 'postcontent', type: 'string'},
            {name: 'posttime', type: 'string'},
            {name: 'commentnum', type: 'number'},
            {name: 'postlikes', type: 'number'},
            {name: 'postliked', type: 'number'},
        ],
        hasMany: { 
            model: 'FreescribbleApp.model.PicModel', 
            name: 'pics',
            associationKey: 'pics',
            autoLoad : true
        }
    }
});