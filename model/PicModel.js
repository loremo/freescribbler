Ext.define('FreescribbleApp.model.PicModel', {
    extend: 'Ext.data.Model',
    config: {
        idProperty: 'picname',
    	fields: [
            {name: 'picname', type: 'string'},
            {name: 'picwidth', type: 'number'},
            {name: 'picheight', type: 'number'}
        ], 
    }
});