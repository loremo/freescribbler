Ext.define("FreescribbleApp.controller.Home2Controller", {
    extend: "Ext.app.Controller",
    requires: ['FreescribbleApp.view.OnePost'],
    config: {
        initial : false,
        fullyLoaded: false,
        loading: false,
        scroller: true,
        refs: {
            homePage: 'homepage2', 
            homeSegmentedBtn: '[itemId = homesegmentedbtn]',
            userContent: '[itemId = userContent]',
            homeTitlebarView: 'titlebarview',
            subscribeBtn: '[itemId = subscribeuser]',
            ignorBtn: '[itemId = ignoruser]'
        },
        
        control: {
            homePage: {
                showUser: 'onShowUser'
            },
            homeSegmentedBtn: {
            	toggle: 'onToggle'
            },
            subscribeBtn: {
                tap: 'onSubscribe'
            },
            ignorBtn: {
            	tap: 'onIgnor'
            }
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
    
    onSubscribe: function () {
        var me = this,
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
                userid: me.getUser()
            },
            success: function (response) {
                var data = Ext.JSON.decode(response.responseText);
                if (data.success) {
                    if (data.response == 'friend added') {
                        me.getSubscribeBtn().setHtml('Удалить подписку');
                    }
                    else {
                        me.getSubscribeBtn().setHtml('Добавить подписку');
                    }
                }
            }
        });
    	
    },
    
    onIgnor: function () {
        var me = this,
            loginSessionStore = Ext.getStore('LogInSession').getData();
        sessionStore = loginSessionStore.items[0].data;
        Ext.Ajax.request({
            url: 'http://freescribbler.com/test.php',
            method: 'post',
            params: {
                act: 'UPDATEIGNOR',
                connectid: sessionStore.connectid,
                token: sessionStore.token,
                clientcode: 123123123,
                userid: me.getUser()
            },
            success: function (response) {
                var data = Ext.JSON.decode(response.responseText);
                if (data.success) {
                    if (data.response == 'ignor added') {
                        me.getSubscribeBtn().setHtml('Разблокировать');
                    }
                    else {
                        me.getSubscribeBtn().setHtml('Заблокировать');
                    }
                }
            }
        });
    	
    },
         
    onToggle: function(container, button, pressed){
            var whichButton = button.getItemId();
            var me = this,
                loginSessionStore = Ext.getStore('LogInSession').getData();
            sessionStore = loginSessionStore.items[0].data;
            var homeStage  = this.getUserContent();
            
            if(pressed === true){
	     	    console.log('pressed:' + whichButton);
                    if(whichButton === "userposts"){
                            var postStore = Ext.create('FreescribbleApp.store.PostStore', {
                                params: {
                                    act: 'GETUSERPOSTS',
                                    connectid: sessionStore.connectid,
                                    token: sessionStore.token,
                                    clientcode: 123123123,
                                    userid: me.getUser()
                                }
                            });
                            
                            homeStage.getComponent('homeDataViewId').destroy();
                            var postView = Ext.create('FreescribbleApp.view.PostDataView');
                            postStore.load();    
                            postStore.on('load', function(){
                                postView.setStore(postStore);
                                homeStage.add(postView);
                            });
                            
                    }
                    else if(whichButton === "usersubscribes"){
                            var subscribesStore = Ext.create('FreescribbleApp.store.UserLightStore', {
                                params: {
                                    act: 'GETFOLLOWED',
                                    connectid: sessionStore.connectid,
                                    token: sessionStore.token,
                                    clientcode: 123123123,
                                    userid: me.getUser()
                                }
                            });
                            homeStage.getComponent('homeDataViewId').destroy();
                            var subscribesView = Ext.create('FreescribbleApp.view.UserLightDataView');
                            subscribesStore.load();    
                            subscribesStore.on('load', function(){
                                subscribesView.setStore(subscribesStore);
                                homeStage.add(subscribesView);
                            });
                    }
                    else if(whichButton === "usersubscribers"){
                            var subscribersStore = Ext.create('FreescribbleApp.store.UserLightStore', {
                                params: {
                                    act: 'GETFOLLOWERS',
                                    connectid: sessionStore.connectid,
                                    token: sessionStore.token,
                                    clientcode: 123123123,
                                    userid: me.getUser()
                                }
                            });
                            homeStage.getComponent('homeDataViewId').destroy();
                            var subscribersView = Ext.create('FreescribbleApp.view.UserLightDataView');
                            subscribersStore.load();    
                            subscribersStore.on('load', function(){
                                subscribersView.setStore(subscribersStore);
                                homeStage.add(subscribersView);
                            });
                    }
            }
    },
        
    onShowUser: function(user) {
        this.setUser(user);
        var me = this,
            loginSessionStore = Ext.getStore('LogInSession').getData();
        sessionStore = loginSessionStore.items[0].data;
        console.log("HOME onActivate...");
        
    	var userInfoContainer  = Ext.ComponentQuery.query('#userInfoWrapper')[0];
        
        var myProfileStore = Ext.create('Ext.data.Store', {
            model: 'FreescribbleApp.model.UserModel',
            proxy: {
                type: 'ajax',
                url: 'http://freescribbler.com/test.php',
                actionMethods: {
                    create : 'POST',
                    read   : 'POST', // by default GET
                    update : 'POST',
                    destroy: 'POST'
                },
                reader: {
                    type: 'json',
                    rootProperty: 'data',
                    successProperty: 'success'
                },
            },
            params: {
                act: 'GETUSER',
                connectid: sessionStore.connectid,
                token: sessionStore.token,
                clientcode: 123123123,
                userid: user
            }
        });
        var homeStage  = this.getUserContent();
        var homeTitlebarView = this.getHomeTitlebarView();
        var userSegmentedBtn = this.getHomeSegmentedBtn();
        userSegmentedBtn.setPressedButtons(0);
        
        myProfileStore.load();
        myProfileStore.on('load', function(){
            
            console.log("try...");
            var myProfile = myProfileStore.getAt(0);
            homeTitlebarView.setTitle(myProfile.data.username);
            userInfoContainer.query('#homeusername')[0].setHtml(myProfile.data.username);
            userInfoContainer.query('#homeprofileImage')[0].setSrc(myProfile.data.useravatar);
            userInfoContainer.query('#userdescription')[0].setHtml(myProfile.data.userdescription);
            if (user != sessionStore.connectid) {
                userInfoContainer.query('#subscribeuser')[0].show();
                userInfoContainer.query('#ignoruser')[0].show();
                if (myProfile.data.mesubscribed == 1) userInfoContainer.query('#subscribeuser')[0].setHtml('Удалить подписку');
                if (myProfile.data.mesubscribed == 0) userInfoContainer.query('#subscribeuser')[0].setHtml('Добавить подписку');
                if (myProfile.data.meignored == 1) userInfoContainer.query('#ignoruser')[0].setHtml('Разблокировать');
                if (myProfile.data.meignored == 0) userInfoContainer.query('#ignoruser')[0].setHtml('Заблокировать');
            }
            else {
                userInfoContainer.query('#subscribeuser')[0].hide();
                userInfoContainer.query('#ignoruser')[0].hide();
            }
            userSegmentedBtn.query('#userposts')[0].setHtml('записи ' + myProfile.data.postnum);
            userSegmentedBtn.query('#usersubscribes')[0].setHtml('подписки ' + myProfile.data.subscribesnum);
            userSegmentedBtn.query('#usersubscribers')[0].setHtml('подписчики ' + myProfile.data.subscribersnum);
        });
        
        var postStore = Ext.create('FreescribbleApp.store.PostStore', {
            params: {
                act: 'GETUSERPOSTS',
                connectid: sessionStore.connectid,
                token: sessionStore.token,
                clientcode: 123123123,
                userid: user
            }
        });
        var postView = Ext.create('FreescribbleApp.view.PostDataView');
        var homeStageInside = homeStage.getComponent('homeDataViewId');
        if (homeStageInside) {
            homeStageInside.destroy();
        }
        postStore.load();
        postStore.on('load', function(){
            console.log('currentView');
            postView.setStore(postStore);
            homeStage.add(postView);
            me.setScroller(me.getHomePage().getScrollable().getScroller());
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
            var homeStage  = me.getUserContent();
            var homeStageInside = homeStage.getComponent('homeDataViewId');
            var homeContentStore = homeStageInside.getStore();
            homeContentStore.load({
                addRecords: true,
            });
            homeContentStore.on('load', function(){
                me.setLoading(false);
            });	
        }
    },
});