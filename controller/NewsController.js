Ext.define("FreescribbleApp.controller.NewsController", {
    extend: "Ext.app.Controller",
    
    config: {
        fullyLoaded: false,
        loading: false,
        scroller: true,
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
            mainFrame: 'newspageview'
        },
        
        control: {
            mainFrame: {
                activate: 'onActivate'
            },
            postPage: {
                showPost: 'onShowPost'
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
    
    onActivate: function (post) {
        var me = this,
            loginSessionStore = Ext.getStore('LogInSession').getData();
        sessionStore = loginSessionStore.items[0].data;
        console.log("News onActivate...");
        var newsStore = Ext.create('FreescribbleApp.store.PostStore', {
            params: {
                act: 'GETFRIENDSPOSTS',
                connectid: sessionStore.connectid,
                token: sessionStore.token,
                clientcode: 123123123
            }
        });
        newsStore.load();
        newsStore.on('load', function(){
            console.log('newsView');
            me.getMainFrame().setStore(newsStore);
            me.setScroller(me.getMainFrame().getScrollable().getScroller());
            me.getScroller().on({
                scrollend: me.onScrollEnd,
                scope: me
            });
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
            });
            commentContentStore.on('load', function(){
                me.setLoading(false);
                commentContentStore.sort('commentid');
            });	
        }
    },
});