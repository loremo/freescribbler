Ext.define('FreescribbleApp.view.UserInfoView', {
    extend: 'Ext.Panel',
    xtype: 'userinfoview',
    
    config: {
    	title: 'User Profile',
        styleHtmlContent: false,
        cls: 'profileWrapper',
        layout: {
            type: 'hbox',
            align: 'stretch'
        },
        items: [{
            xtype: 'container',
            cls: 'leftprofilepart',
            width: 110,
            items: [  
                {
                    xtype: 'image',
                    name : 'homeprofileImage',
                    itemId: 'homeprofileImage',
                    cls: 'profileImage'
                },
                {
                    xtype: 'button',
                    name:'writepost',
                    itemId:'writepost',
                    cls: 'writepostBtn'
                },
                
             ]
        },
        {
            xtype: 'container',
            cls: 'rightprofilepart',
            flex: 1,
            items: [  
                {
                    xtype: 'label',
                    name:'homeusername',
                    itemId:'homeusername',
                    cls: 'profileUsername'

                },
                {
                    xtype: 'label',
                    name:'moneylabel',
                    itemId:'moneylabel',
                    cls: 'profileMoneyLabel'

                },
                {
                    xtype: 'button',
                    name:'moneybutton',
                    itemId:'moneybutton',
                    cls: 'profileMoneyButton',
                    text: 'снять деньги'
                },
                {
                    xtype: 'component',
                    name:'userdescription',
                    itemId:'userdescription',
                    cls: 'profileUserDescription'
                },
                {
                    xtype: 'segmentedbutton',
	            allowDepress: false,
	            layout: {
                        pack: 'center',
                        align: 'stretch'
                    },
	            itemId:'homesegmentedbtn',
                    cls: 'topButtonBlock',
                    height: 45,
                    items: [{
                            itemId: 'userposts',
                            pressed: true,
                            cls:'userposts',
                            flex: 1
                        },
                        {
                            itemId:'usersubscribes',
                            cls:'usersubscribes',
                            flex: 1
                        },
                        {
                            itemId:'usersubscribers',
                            cls:'usersubscribers',
                            flex: 1
                        },
                    ]
                },
                {
                    xtype: 'container',
                    layout: {
                        type: 'hbox',
                        align: 'stretch'
                    },
                    cls: 'bottomButtonBlock',
                    height: 45,
                    items: [{
                            xtype: 'button',
                            name:'subscribeuser',
                            itemId:'subscribeuser',
                            ui: 'flat',
                            cls:'subscribeuser',
                            flex: 1
                        },
                        {
                            xtype: 'button',
                            name:'ignoruser',
                            itemId:'ignoruser',
                            ui: 'flat',
                            cls:'ignoruser',
                            flex: 1
                        }
                    ]
                },
             ] 
        }]
    }
});
