Ext.define('FreescribbleApp.view.CommentItem', {
    extend: 'Ext.dataview.component.DataItem',
    xtype: 'commentitem',
    requires: ['Ext.field.Text', 'Ext.Img', 'Ext.Container', 'Ext.Button', 'Ext.Label'],
    config: {
        styleHtmlContent: true,
        cls: 'commentWrapper',
        commenterAvatar: {
            cls: 'commentAvatar'
        },
        commenterName: {
            cls: 'commentName',
            ui: 'plain'
        },
        commentTime: {
            cls: 'commentTime'
        },
        commentDeleteBtn: {
            cls: 'commentDeleteBtn'
        },
        commentContent: {
            cls: 'commentContent'
        },
        dataMap: {
            getCommenterName: {
                setHtml: 'username'
            },
            getCommenterAvatar: {
                setSrc: 'useravatar'
            },
            getCommentTime: {
                setHtml: 'commenttime'
            },
            getCommentContent: {
                setHtml: 'commentcontent'
            }
        },
    },
    applyCommenterName: function(config) {
        return Ext.factory(config, Ext.Button, this.getCommenterName());
    },
    updateCommenterName: function(newCommenterName, oldCommenterName) {
        
        if (newCommenterName) {
            newCommenterName.on('tap', this.onCommenterTap, this);

            this.add(newCommenterName);
        }
        if (oldCommenterName) {
            this.remove(oldCommenterName);
        }
    },
    applyCommenterAvatar: function(config) {
        return Ext.factory(config, Ext.Img, this.getCommenterAvatar());
    },
    updateCommenterAvatar: function(newCommenterAvatar, oldCommenterAvatar) {
        
        if (newCommenterAvatar) {
            newCommenterAvatar.on('tap', this.onCommenterTap, this);
            this.add(newCommenterAvatar);
        }
        if (oldCommenterAvatar) {
            this.remove(oldCommenterAvatar);
        }
    },
    
    onCommenterTap: function(button, e) {
        var record = this.getRecord(), me = this;
        
        var upperview = me.getDataview().up().up().up();
        upperview.fireEvent('showUser', record.get('userid'));
        
    	
    },
    
    applyCommentTime: function(config) {
        return Ext.factory(config, Ext.Component, this.getCommentTime());
    },
    updateCommentTime: function(newCommentTime, oldCommentTime) {
        
        if (newCommentTime) {
            this.add(newCommentTime);
        }
        if (oldCommentTime) {
            this.remove(oldCommentTime);
        }
    },
    
    applyCommentContent: function(config) {
        return Ext.factory(config, Ext.Component, this.getCommentContent());
    },
    updateCommentContent: function(newCommentContent, oldCommentContent) {
        
        if (newCommentContent) {
            this.add(newCommentContent);
        }
        if (oldCommentContent) {
            this.remove(oldCommentContent);
        }
    },
    applyCommentDeleteBtn: function (config) {
        if (this.getRecord().get('userid') == localStorage.getItem('userid')) 
            return Ext.factory(config, Ext.Button, this.getCommentDeleteBtn());
    },

    updateCommentDeleteBtn: function (newCommentDeleteBtn, oldCommentDelteButton) {
        if (oldCommentDelteButton) {
            this.remove(oldCommentDelteButton);
        }

        if (newCommentDeleteBtn) {
            newCommentDeleteBtn.on('tap', this.onCommentDeleteBtnTap, this);

            this.add(newCommentDeleteBtn);
        }
    },
    
    onCommentDeleteBtnTap: function(button, e) {
        var record = this.getRecord(), me = this;

        Ext.Msg.confirm(null,
            "Do you want to remove?",
            function (answer) {

                if (answer === 'yes') {
                    me.getDataview().getStore().remove(record);
                    me.getDataview().getStore().getProxy().setExtraParams({
                        user: localStorage.getItem('userId'),
                        token: localStorage.getItem('token')
                    });
                    me.getDataview().getStore().sync();
                    me.destroy();
                }
            }
        );
    }
});
