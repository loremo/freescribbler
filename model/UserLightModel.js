Ext.define('FreescribbleApp.model.UserLightModel', {
    extend: 'Ext.data.Model',
    config: {
        idProperty: 'userid',
    	fields: [
            {name: 'userid', type: 'number' },
            {name: 'useravatar', type: 'string' },
            {name: 'username', type: 'string' },
            {name: 'isprivate', type: 'number' },
            {name: 'mysubscriber', type: 'number' },
            {name: 'mesubscribed', type: 'number' },
            {name: 'myignorer', type: 'number' },
            {name: 'meignored', type: 'number' },
        ]
    }
});