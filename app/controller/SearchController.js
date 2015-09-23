Ext.define("FreescribbleApp.controller.SearchController", {
    extend: "Ext.app.Controller",
    config: {
        initial : false,
        fullyLoaded: false,
        loading: false,
        scroller: true,
        target: null,
        refs: {
            searchPage: 'searchpage', 
            searchContent: '[itemId = searchContent]',
            postSearchBtn: '[itemId = postSearchBtn]',
            userSearchBtn: '[itemId = userSearchBtn]',
            searchField: '[itemId = searchField]',
            searchStartBtn: '[itemId = searchStartBtn]',
        },
        
        control: {
            searchPage: {
                activate: 'onActivate'
            },
            postSearchBtn: {
                tap: 'onShowPostsTap'
            },
            userSearchBtn: {
                tap: 'onShowUsersTap'
            },
            searchStartBtn: {
                tap: 'onSearchStartBtnTap'
            }
        },
    },
    init: function () {
    	console.log('INIT from EventController');
        //this.setInitial(true);
    },
    	 
    launch: function () {
        console.log('LAUNCH from EventController');
    },
    
    onSearchStartBtnTap: function() {
        console.log('start onSearchStartBtnTap');
        var me = this,
            loginSessionStore = Ext.getStore('LogInSession').getData();
            sessionStore = loginSessionStore.items[0].data;
        var searchField = me.getSearchField().getValue();
        if (searchField && searchField != '') {
            if (target == 'posts') me.onShowPosts(searchField);
            if (target == 'users') me.onShowUsers(searchField);
        }
    },
    
    onShowPostsTap: function() {
        target = 'posts';
        this.onShowPosts(null);
    },
        
    onShowPosts: function(hash) {
        console.log('start onShowPosts');
        var me = this,
            loginSessionStore = Ext.getStore('LogInSession').getData();
            sessionStore = loginSessionStore.items[0].data,
            searchStore = null;
      
        var searchStage  = this.getSearchContent();
        if (hash) {
            searchStore = Ext.create('FreescribbleApp.store.PostStore', {
                params: {
                    act: 'GETHASHPOSTS',
                    connectid: sessionStore.connectid,
                    token: sessionStore.token,
                    clientcode: 123123123,
                    hashtag: hash
                }
            });
        }
        else {
            searchStore = Ext.create('FreescribbleApp.store.PostStore', {
                params: {
                    act: 'GETBESTPOSTS',
                    connectid: sessionStore.connectid,
                    token: sessionStore.token,
                    clientcode: 123123123,
                }
            });
        }
        searchStore.setStoreId('searchstore');
        var searchView = Ext.create('FreescribbleApp.view.PostDataView');
        
        searchStage.removeAll();
        searchStore.load();
        searchStore.on('load', function(){
            console.log('currentSearchView');
            searchView.setStore(searchStore);
            searchStage.add(searchView);
            me.setScroller(me.getSearchPage().getScrollable().getScroller());
            me.getScroller().on({
                scrollend: me.onScrollEnd,
                scope: me
            });
        });	
    },
    
    onShowUsersTap: function() {
        target = 'users';
        this.onShowUsers(null);
    },
        
    onShowUsers: function(user) {
        console.log(user);
        var me = this,
            loginSessionStore = Ext.getStore('LogInSession').getData();
            sessionStore = loginSessionStore.items[0].data,
            searchStore = null;
      
        var searchStage  = this.getSearchContent();
        if (user) {
            searchStore = Ext.create('FreescribbleApp.store.UserLightStore', {
                params: {
                    act: 'FINDUSER',
                    connectid: sessionStore.connectid,
                    token: sessionStore.token,
                    clientcode: 123123123,
                    username: user
                }
            });
        }
        else {
            searchStore = Ext.create('FreescribbleApp.store.UserLightStore', {
                params: {
                    act: 'GETLASTUSERS',
                    connectid: sessionStore.connectid,
                    token: sessionStore.token,
                    clientcode: 123123123
                }
            });
        }
        searchStore.setStoreId('searchstore');
        var searchView = Ext.create('FreescribbleApp.view.UserLightDataView');
        
        searchStage.removeAll();
        searchStore.load();
        searchStore.on('load', function(){
            console.log('currentSearchView');
            searchView.setStore(searchStore);
            searchStage.add(searchView);
            me.setScroller(me.getSearchPage().getScrollable().getScroller());
            me.getScroller().on({
                scrollend: me.onScrollEnd,
                scope: me
            });
        });	
    },
    
    
        
    onActivate: function() {
        console.log('start onShowUsers');
        var me = this,
            loginSessionStore = Ext.getStore('LogInSession').getData();
            sessionStore = loginSessionStore.items[0].data;
        target = 'posts';
        me.onShowPosts(null);
    },
    
    onScrollEnd: function(scroller, x, y) {
        var me = this;
        if (!me.getFullyLoaded() && !me.getLoading() && y >= scroller.maxPosition.y) {
            me.setLoading(true);
            var eventContentStore = me.getEventContent().getComponent('eventDataViewId').getStore();
            eventContentStore.load({
                addRecords: true,
            });
            eventContentStore.on('load', function(){
                me.setLoading(false);
            });	
        }
    },
});