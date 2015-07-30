Ext.define('FreescribbleApp.model.LogInCredential', {
    extend: 'Ext.data.Model',
    config: {
        fields: [
            { name: 'connectid' }, 
            { name: 'token' }
        ]
    }
});