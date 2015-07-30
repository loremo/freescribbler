Ext.define('FreescribbleApp.store.LogInSession', {
    extend: 'Ext.data.Store',
    requires: [
        'Ext.data.proxy.SessionStorage'
    ],
    config: {
        model: 'FreescribbleApp.model.LogInCredential',
        autoLoad: true,
        // loginsessionstorage will be auto sync when this store is updated
        autoSync: true,
        proxy: {
            type: 'sessionstorage',
            id: 'loginsessionstorage'
        }
    }
});