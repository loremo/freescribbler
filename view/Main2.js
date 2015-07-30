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
                xtype: 'newspageview',
                itemId: 'newsWrapper'
            },
            /*{
                xtype: 'makepostpage',
                itemId: 'homeWrapper'
            },
            {
                xtype: 'eventpage',
                itemId: 'eventWrapper'
            }*/
        ]
    }
});
