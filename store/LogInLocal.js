Ext.define('FreescribbleApp.store.LogInLocal', {
    extend: 'Ext.data.Store',
    requires: [
        'Ext.data.proxy.LocalStorage'
    ],
    config: {
        model: 'FreescribbleApp.model.LogInCredential',
        autoLoad: true,
        // loginlocalstorage will be auto sync when this store is updated
        autoSync: true,
        proxy: {
            type: 'localstorage',
            id: 'loginlocalstorage'
        }
    }
});