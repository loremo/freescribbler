Ext.define('FreescribbleApp.view.EventItem', {
    extend: 'Ext.dataview.component.DataItem',
    xtype: 'eventitem',
    requires: ['Ext.field.Text', 'Ext.Img', 'Ext.Container', 'Ext.Button', 'Ext.Label'],
    config: {
        styleHtmlContent: true,
        cls: 'eventItem',
        avatar: {
            cls: 'eventSubjectAvatar'
        },
        authorName: {
            cls: 'eventSubjectName',
            ui: 'plain'
        },
        eventTime: {
            cls: 'eventTime'
        },
        eventType: {
            cls: 'eventType'
        },
        eventPost: {
            cls: 'eventPost'
        },
        eventComment: {
            cls: 'eventComment'
        },
        dataMap: {
            getAuthorName: {
                setHtml: 'username'
            },
            getAvatar: {
                setSrc: 'useravatar'
            },
            getEventTime: {
                setHtml: 'eventtime'
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
    
    applyEventTime: function(config) {
        return Ext.factory(config, Ext.Component, this.getEventTime());
    },
    updateEventTime: function(newEventTime, oldEventTime) {
        
        if (newEventTime) {
            this.add(newEventTime);
        }
        if (oldEventTime) {
            this.remove(oldEventTime);
        }
    },
});
