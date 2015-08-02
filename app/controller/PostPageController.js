Ext.define("FreescribbleApp.controller.PostPageController", {
    extend: "Ext.app.Controller",
    
    config: {
        fullyLoaded: false,
        loading: false,
        refs: {
            postPage: 'postpage',
            homeWrapper: 'homepage',
            avatar: '[itemId = posterAvatar]',
            backBtn: '[itemId = searchBackBtn]',
            posterName: '[itemId = posterName]',
            postTime: '[itemId = postTime]',
            postEditBtn: '[itemId = postEditBtn]',
            postPics: '[itemId = postPics]',
            postContent: '[itemId = postContent]',
            postLikeBtn: '[itemId = postLikeBtn]',
            postComment: '[itemId = postComment]',
            commentStage: '[itemId = commentBlock]',
            moreComments: '[itemId = moreCommentsBtn]',
        },
        
        control: {
            postPage: {
                active123: 'onShowPost'
            },
            backBtn: {
            	tap: 'backBtnTaped'
            },
            searchSegmentedBtn: {
            	toggle: 'onToggle'
            },
            searchBtn:{
            	tap: 'searchBtnTaped'
            },
            searchField:{
            	keyup: 'searchFieldChanged'
            },
            avatar: {
                tap: 'onUserTap'
            },
            posterName: {
                tap: 'onUserTap'
            },
            postLikeBtn: {
                tap: 'onPostLikeBtnTap'
            }
        },
        post: null,
    },
    init: function () {
    	},
    	 
    launch: function () {
    	 },
    	 
    backBtnTaped: function (){
    	console.log('backBtnTaped');
    	Ext.Viewport.animateActiveItem(this.getMain(), {type: 'slide', direction: 'right', duration: 400}) ;
    	    	 },
    
    clear: function(component){
    	component.removeAll(true);
    	component.setHtml('');
    },
    
    onShowPost: function (post) {
        var me = this;
        me.setPost(post);
        console.log('onShowPost2');
        me.getAvatar().setSrc(post.get('avatar'));
        me.getPosterName().setHtml(post.get('username'));
        me.getPostTime().setHtml(post.get('posttime'));
        
        var picRecords = post.pics();
        var picNum = picRecords.getCount();
        var a = [];
        picRecords.each(function(r,i){
            a[i] = r.data.picwidth/r.data.picheight;
        });
        me.getPostPics().removeAll();
        picRecords.each(function(r,i){
            var width = 0;
            var height = 0;

            height = 100 / a[i];
            width = height*a[i];

            var picImage = Ext.create('Ext.Container',
            {
                cls: 'postOnePicWrapper',
                height: height + '%',
                width: width + '%',
                html: '<img src="' + r.data.picname + '" class="postOnePic">'
            });
            
            me.getPostPics().add(picImage);
            
        });
        
        var commentStage  = me.getCommentStage();
        me.getPostContent().setHtml(post.get('postcontent'));
        me.getPostLikeBtn().setHtml(post.get('postlikes'));
        me.getPostComment().setHtml(post.get('commentnum'));
        me.getPostLikeBtn().element.on('tap', this.onPostLikeBtnTap, this);
        
        var commentStore = Ext.create('FreescribbleApp.store.CommentStore', {
            params: {
                act: 'GETCOMMENTS',
                connectid: sessionStore.connectid,
                token: sessionStore.token,
                clientcode: 123123123,
                postid: post.get('postid')
            }
        });
        commentStore.getProxy().setExtraParams({
            limit: 10
        });
        var commentView = Ext.create('FreescribbleApp.view.CommentDataView');
        commentStage.removeAll();
        me.getMoreComments().show();
        me.getMoreComments().element.on('tap', this.onMoreComments, this);
        if (post.get('commentnum') < 10) {
            console.log(post.get('commentnum'));
            me.getMoreComments().hide();
        }
        commentStore.load();
        commentStore.on('load', function(){
            console.log('currentView');
            commentView.setStore(commentStore);
            commentStage.add(commentView);
            commentStore.sort('commentid');
        });	
    },
    
    onUserTap: function (post) {
        var me = this;
        console.log(me.getPost());
        me.getHomeWrapper().fireEvent('showUser', me.getPost().get('userid'));
    },
    
    onPostLikeBtnTap: function(button, e) {
        console.log('onPostLikeBtnTap');
        var me = this,
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
                postid: me.getPost().get('postid')
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
    
    onMoreComments: function() {
        var me = this;
        if (!me.getFullyLoaded() && !me.getLoading()) {
            me.setLoading(true);
            var commentStage  = me.getCommentStage();
            var commentStageInside = commentStage.getComponent('commentDataViewId');
            var commentContentStore = commentStageInside.getStore();
            commentContentStore.load({
                addRecords: true,
                callback: function(records, operation, success) {
                    // the operation object contains all of the details of the load operation
                    if (records.length < 30) {
                        me.getMoreComments().hide();
                        me.setFullyLoaded(true);
                    }
                    commentContentStore.sort('commentid');
                },
            });
        }
    },
});