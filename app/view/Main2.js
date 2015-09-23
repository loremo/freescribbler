Ext.define('FreescribbleApp.view.Main2', {
    extend: 'Ext.tab.Panel',
    xtype: 'main2',
    config: {
        tabBarPosition: 'bottom',
        items: [
            {
                xtype: 'homepage',
                itemId: 'homeWrapper'
            },
            {
                xtype: 'newspage',
                itemId: 'newsWrapper'
            },
            {
                xtype: 'newpost',
                itemId: 'newPostWrapper'
            },
            {
                xtype: 'eventpage',
                itemId: 'eventWrapper'
            },
            {
                xtype: 'searchpage',
                itemId: 'searchWrapper'
            }
        ]
    }
});
