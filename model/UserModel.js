Ext.define('FreescribbleApp.model.UserModel', {
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
            {name: 'userdescription', type: 'string' },
            {name: 'moneyprivate', type: 'number' },
            {name: 'postnum', type: 'number' },
            {name: 'subscribersnum', type: 'number' },
            {name: 'subscribesnum', type: 'number' },
            {name: 'usermoney', type: 'string' },
            {name: 'walletwebmoney', type: 'string' },
            {name: 'walletpaypal', type: 'string' }
        ]
    }
});