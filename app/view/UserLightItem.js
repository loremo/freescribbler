Ext.define('FreescribbleApp.view.UserLightItem', {
    extend: 'Ext.dataview.component.DataItem',
    xtype: 'userlightitem',
    requires: ['Ext.field.Text', 'Ext.Img', 'Ext.Container', 'Ext.Button', 'Ext.Label'],
    config: {
        styleHtmlContent: true,
        cls: 'userLightItem',
        avatar: {
            cls: 'userAvatar'
        },
        authorName: {
            cls: 'posterName',
            ui: 'plain'
        },
        changeUser: {
            cls: 'userLightAdd',
            text: 'Добавить'
        },
        dataMap: {
            getAuthorName: {
                setHtml: 'username'
            },
            getAvatar: {
                setSrc: 'useravatar'
            }
        },
    },
    applyAuthorName: function(config) {
        return Ext.factory(config, Ext.Button, this.getAuthorName());
    },
    updateAuthorName: function(newAuthorName, oldAuthorName) {
        
        if (newAuthorName) {
            newAuthorName.on('tap', this.onAuthorTap, this);

            this.add(newAuthorName);
        }
        if (oldAuthorName) {
            this.remove(oldAuthorName);
        }
    },
    applyAvatar: function(config) {
        return Ext.factory(config, Ext.Img, this.getAvatar());
    },
    updateAvatar: function(newAvatar, oldAvatar) {
        
        if (newAvatar) {
            newAvatar.on('tap', this.onAuthorTap, this);
            this.add(newAvatar);
        }
        if (oldAvatar) {
            this.remove(oldAvatar);
        }
    },
    
    onAuthorTap: function(button, e) {
        var record = this.getRecord(), me = this;
        
        var upperview = me.getDataview().up().up();
        upperview.fireEvent('showUser', record.get('userid'));
        
    	
    },
    
    applyChangeUser: function (config) {
        return Ext.factory(config, Ext.Button, this.getChangeUser());
    },

    updateChangeUser: function (newChangeUser, oldChangeUser) {
        var record = this.getRecord(),
                me = this;
        if (oldChangeUser) {
            this.remove(oldChangeUser);
        }

        if (newChangeUser) {
            if (record.get('mesubscribed') == 1) {
                newChangeUser.removeCls('userLightAdd');
                newChangeUser.addCls('userLightRemove');
                newChangeUser.setText('Удалить');
            }
            newChangeUser.element.on('tap', this.onChangeUserTap, this);
            this.add(newChangeUser);
        }
    },
    
    onChangeUserTap: function(button, e) {
        var record = this.getRecord(), 
            me = this,
            loginSessionStore = Ext.getStore('LogInSession').getData();
        sessionStore = loginSessionStore.items[0].data;
        
        Ext.Ajax.request({
            url: 'http://freescribbler.com/test.php',
            method: 'post',
            params: {
                act: 'UPDATEFRIEND',
                connectid: sessionStore.connectid,
                token: sessionStore.token,
                clientcode: 123123123,
                userid: record.get('userid')
            },
            success: function (response) {
                var data = Ext.JSON.decode(response.responseText);
                if (data.success) {
                    var friendBtn = me.getChangeUser();
                    if (data.response == 'friend added') {
                        friendBtn.removeCls('userLightAdd');
                        friendBtn.addCls('userLightRemove');
                        friendBtn.setText('Удалить');
                    }
                    else {
                        friendBtn.removeCls('userLightRemove');
                        friendBtn.addCls('userLightAdd');
                        friendBtn.setText('Добавить');
                    }
                }
            }
        });        
    }
});
