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
    
    applyEventType: function(config) {
        return Ext.factory(config, Ext.Img, this.getEventType());
    },
    updateEventType: function(newEventType, oldEventType) {
        var record = this.getRecord(),
                me = this,
                recordType = record.get('eventart');
        if (newEventType) {
            if (recordType == 'FRIEND') {
                newEventType.setSrc('../../freescribble/mockdata/img/freescribbler_add.png');
            }
            if (recordType == 'DA') {
                newEventType.setSrc('../../freescribble/mockdata/img/freescribbler_like1.png');
            }
            if (recordType == 'COMMENT') {
                newEventType.setSrc('../../freescribble/mockdata/img/freescribbler_comment1.png');
            }
            if (recordType == 'LINK') {
                newEventType.setSrc('../../freescribble/mockdata/img/freescribbler_link.png');
            }
            this.add(newEventType);
        }
        if (oldEventType) {
            this.remove(oldEventType);
        }
    },
    
    applyEventPost: function(config) {
        return Ext.factory(config, Ext.Component, this.getEventPost());
    },
    updateEventPost: function(newEventPost, oldEventPost) {
        var record      = this.getRecord(),
                me      = this,
        recordPostId    = record.get('articleid'),
        recordPostCont  = record.get('articlecontent'),
        picRecords      = record.pics();
        if (newEventPost) {
            if (recordPostId != -1) {
                if (picRecords.first()) {
                    console.log(picRecords.first());
                    newEventPost.setHtml('<div style="background-image: url(' + picRecords.first().data.picname + ')" class="eventPic"></div>');
                }
                else {
                    newEventPost.setHtml(recordPostCont);
                }
            }
            this.add(newEventPost);
        }
        if (oldEventPost) {
            this.remove(oldEventPost);
        }
    },
    
    applyEventComment: function(config) {
        return Ext.factory(config, Ext.Component, this.getEventComment());
    },
    updateEventComment: function(newEventComment, oldEventComment) {
        var record          = this.getRecord(),
                me          = this,
        recordCommentId     = record.get('commentid'),
        recordCommentCont   = record.get('commentcontent');
        if (newEventComment) {
            if (recordCommentId != -1) {
                newEventComment.setHtml(recordCommentCont);
            }
            this.add(newEventComment);
        }
        if (oldEventComment) {
            this.remove(oldEventComment);
        }
    },
});
