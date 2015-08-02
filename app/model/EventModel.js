Ext.define('FreescribbleApp.model.EventModel', {
    extend: 'Ext.data.Model',
    config: {
        idProperty: 'eventid',
    	fields: [
            {name: 'useravatar', type: 'string'},
            {name: 'userid', type: 'number'},
            {name: 'username', type: 'string'},
            {name: 'eventid', type: 'number'},
            {name: 'eventart', type: 'string'},
            {name: 'eventtime', type: 'string'},
            {name: 'articleid', type: 'number'},
            {name: 'commentid', type: 'number'},
            {name: 'articlecontent', type: 'string'},
            {name: 'commentcontent', type: 'string'},
        ],
        hasMany: { 
            model: 'FreescribbleApp.model.PicModel', 
            name: 'pics',
            associationKey: 'pics',
            autoLoad : true
        }
    }
});