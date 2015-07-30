Ext.define('FreescribbleApp.model.CommentModel', {
    extend: 'Ext.data.Model',
    config: {
        idProperty: 'commentid',
    	fields: [
            {name: 'useravatar', type: 'string'},
            {name: 'userid', type: 'number'},
            {name: 'username', type: 'string'},
            {name: 'commentid', type: 'number'},
            {name: 'commentcontent', type: 'string'},
            {name: 'commenttime', type: 'string'}
        ]
    }
});