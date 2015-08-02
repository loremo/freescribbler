Ext.define("FreescribbleApp.controller.NewsController", {
    extend: "Ext.app.Controller",
    requires: ['FreescribbleApp.view.OnePost'],
    config: {
        initial : false,
        fullyLoaded: false,
        loading: false,
        scroller: true,
        refs: {
            newsPage: 'newspage', 
            userContent: '[itemId = newsContent]',
        },
        
        control: {
            newsPage: {
                activate: 'onShowUser'
            },
        },
        user: null,
    },
    init: function () {
    	console.log('INIT from HomeController');
        //this.setInitial(true);
    },
    	 
    launch: function () {
        console.log('LAUNCH from HomeController');
    },
        
    onShowUser: function(user) {
        
        var me = this,
            loginSessionStore = Ext.getStore('LogInSession').getData();
        sessionStore = loginSessionStore.items[0].data;
        this.setUser(sessionStore.connectid);
      
        var newsStage  = this.getUserContent();
        
        var postStore = Ext.create('FreescribbleApp.store.PostStore', {
            params: {
                act: 'GETFRIENDSPOSTS',
                connectid: sessionStore.connectid,
                token: sessionStore.token,
                clientcode: 123123123
            }
        });
        postStore.setStoreId('newsstore');
        var postView = Ext.create('FreescribbleApp.view.PostDataView');
        
        newsStage.removeAll();
        postStore.load();
        postStore.on('load', function(){
            console.log('currentView');
            postView.setStore(postStore);
            newsStage.add(postView);
            me.setScroller(me.getNewsPage().getScrollable().getScroller());
            me.getScroller().on({
                scrollend: me.onScrollEnd,
                scope: me
            });
        });	
    },
    
    onScrollEnd: function(scroller, x, y) {
        var me = this;
        if (!me.getFullyLoaded() && !me.getLoading() && y >= scroller.maxPosition.y) {
            me.setLoading(true);
            var newsContentStore = me.getNewsContent().getComponent('homeDataViewId').getStore();
            newsContentStore.load({
                addRecords: true,
            });
            newsContentStore.on('load', function(){
                me.setLoading(false);
            });	
        }
    },
});