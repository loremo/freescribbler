Ext.define('FreescribbleApp.view.PostItem', {
    extend: 'Ext.dataview.component.DataItem',
    xtype: 'postitem',
    requires: ['Ext.field.Text', 'Ext.Img', 'Ext.Container', 'Ext.Button', 'Ext.Label'],
    config: {
        styleHtmlContent: true,
        cls: 'postWrapper',
        avatar: {
            cls: 'postAvatar'
        },
        authorName: {
            cls: 'posterName',
            ui: 'plain'
        },
        postTime: {
            cls: 'postTime'
        },
        postDeleteBtn: {
            cls: 'postDeleteBtn'
        },
        postPics: {
            cls: 'postPics'
        },
        postContent: {
            cls: 'postContent'
        },
        postSeparator: {
            cls: 'postSeparator'
        },
        postLikeBtn: {
            cls: 'postLikeBtn'
        },
        postComment: {
            cls: 'postComment'
        },
        dataMap: {
            getAuthorName: {
                setHtml: 'username'
            },
            getAvatar: {
                setSrc: 'postavatar'
            },
            getPostTime: {
                setHtml: 'posttime'
            },
            getPostContent: {
                setHtml: 'postcontent'
            },
            getPostLikeBtn: {
                setHtml: 'postlikes'
            },
            getPostComment: {
                setHtml: 'commentnum'
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
        var upperview = me.getDataview().up('main2').down('homepage');
        upperview.fireEvent('showUser', record.get('userid'));
        
    	
    },
    
    applyPostTime: function(config) {
        return Ext.factory(config, Ext.Component, this.getPostTime());
    },
    updatePostTime: function(newPostTime, oldPostTime) {
        
        if (newPostTime) {
            this.add(newPostTime);
        }
        if (oldPostTime) {
            this.remove(oldPostTime);
        }
    },
    applyPostPics: function(config) {
        return Ext.factory(config, Ext.Container, this.getPostPics());
    },
    updatePostPics: function(newPostPics, oldPostPics) {
        var record = this.getRecord();
        if (newPostPics) {
            var picRecords = record.pics();
            var picNum = picRecords.getCount();
            var a = [];
            picRecords.each(function(r,i){
                a[i] = r.data.picwidth/r.data.picheight;
            });
            picRecords.each(function(r,i){
                var width = 0;
                var height = 0;

                if (picNum === 1) height = 100 / a[0];
                if (picNum === 2) height = 99 / (a[1] + a[0]);
                if (picNum === 3) height = 98 / (a[2] + a[1] + a[0]);
                if (picNum > 3) {
                    if (i < 2) height = 99 / (a[1] + a[0]);
                    else {
                        var a_sum = 0;
                        var full_width = 101;
                        for (j = 2; j < picNum; j++) {
                            a_sum = a_sum + a[j];
                            full_width = full_width - 1;
                        }
                        height = full_width / a_sum;
                    }
                }

                width = height*a[i];

                var picImage = Ext.create('Ext.Container',
                {
                    cls: 'postOnePicWrapper',
                    height: height + '%',
                    width: width + '%',
                    html: '<img src="' + r.data.picname + '" class="postOnePic">'
                });

                var picSeparator = Ext.create('Ext.Container',
                {
                    cls: 'postOnePicWrapper',
                    height: height + '%',
                    width: '1%',
                });

                newPostPics.add(picImage);
                if ((i+1 === picNum) || (i === 1 && picNum > 3)) {

                }
                else {newPostPics.add(picSeparator)}
            });
            this.insert(5,newPostPics);
            
        }
        if (oldPostPics) {
            this.remove(oldPostPics);
        }
    },
    applyPostContent: function(config) {
        return Ext.factory(config, Ext.Component, this.getPostContent());
    },
    updatePostContent: function(newPostContent, oldPostContent) {
        
        if (newPostContent) {
            this.add(newPostContent);
        }
        if (oldPostContent) {
            this.remove(oldPostContent);
        }
    },
    applyPostDeleteBtn: function (config) {
        if (this.getRecord().get('userId') == localStorage.getItem('userId')) 
            return Ext.factory(config, Ext.Button, this.getPostDeleteBtn());
    },

    updatePostDeleteBtn: function (newPostDeleteBtn, oldPostDelteButton) {
        if (oldPostDelteButton) {
            this.remove(oldPostDelteButton);
        }

        if (newPostDeleteBtn) {
            newPostDeleteBtn.on('tap', this.onPostDeleteBtnTap, this);

            this.add(newPostDeleteBtn);
        }
    },
    
    onPostDeleteBtnTap: function(button, e) {
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
    },
    
    applyPostSeparator: function (config) {
        return Ext.factory(config, Ext.Component, this.getPostSeparator());
    },

    updatePostSeparator: function (newPostSeparator, oldDelteButton) {
        if (oldDelteButton) {
            this.remove(oldDelteButton);
        }

        if (newPostSeparator) {
            this.add(newPostSeparator);
        }
    },
    applyPostLikeBtn: function (config) {
        return Ext.factory(config, Ext.Component, this.getPostLikeBtn());
    },

    updatePostLikeBtn: function (newPostLikeBtn, oldPostLikeBtn) {
        var record = this.getRecord();
        if (oldPostLikeBtn) {
            this.remove(oldPostLikeBtn);
        }

        if (newPostLikeBtn) {
            if (record.get('postLiked') == 1) {
                newPostLikeBtn.removeCls('postLikeBtn');
                newPostLikeBtn.addCls('postLikeBtn2');
            }
            newPostLikeBtn.element.on('tap', this.onPostLikeBtnTap, this);
            this.add(newPostLikeBtn);
        }
    },
    
    onPostLikeBtnTap: function(button, e) {
        var record = this.getRecord(), 
            me = this,
            loginSessionStore = Ext.getStore('LogInSession').getData();
        sessionStore = loginSessionStore.items[0].data;
        
        Ext.Ajax.request({
            url: 'http://freescribbler.com/test.php',
            method: 'post',
            params: {
                act: 'UPDATELIKE',
                connectid: sessionStore.connectid,
                token: sessionStore.token,
                clientcode: 123123123,
                postid: record.get('postid')
            },
            success: function (response) {
                var data = Ext.JSON.decode(response.responseText);
                if (data.success) {
                    var likeBtn = me.getPostLikeBtn();
                    if (data.response == 'like added') {
                        likeBtn.removeCls('postLikeBtn');
                        likeBtn.addCls('postLikeBtn2');
                        likeBtn.setHtml(data.data);
                    }
                    else {
                        likeBtn.removeCls('postLikeBtn2');
                        likeBtn.addCls('postLikeBtn');
                        likeBtn.setHtml(data.data);
                    }
                }
            }
        }); 
    },
    
    applyPostComment: function (config) {
        return Ext.factory(config, Ext.Component, this.getPostComment());
    },

    updatePostComment: function (newPostComment, oldDelteButton) {
        if (oldDelteButton) {
            this.remove(oldDelteButton);
        }

        if (newPostComment) {
            newPostComment.element.on('tap', this.onPostTap, this);
            this.add(newPostComment);
        }
    },
    
    onPostTap: function(button, e) {
        var record = this.getRecord(), me = this;
        console.log('onPostTap');
        var upperview = me.getDataview().up('main2').down('homepage');
        upperview.fireEvent('showPost', record);
    },
});
