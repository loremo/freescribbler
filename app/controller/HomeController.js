Ext.define("FreescribbleApp.controller.HomeController", {
    extend: "Ext.app.Controller",
    requires: ['FreescribbleApp.view.OnePost'],
    config: {
        initial : false,
        fullyLoaded: false,
        loading: false,
        scroller: true,
        refs: {
            homePage: 'homepage2', 
            postPage: 'postpage',
            mainFrame: 'homepage',
            superMain: 'main2',
        },
        
        control: {
            mainFrame: {
                showUser: 'onShowUser',
                showPost: 'onShowPost'
            }
        }
    },
    init: function () {
    	console.log('INIT from HomeFrController');
        //this.setInitial(true);
    },
    	 
    launch: function () {
        console.log('LAUNCH from HomeFrController');
        //this.getMainFrame().on('activate', this.onActivate, this);
        this.getMainFrame().on('activate', this.onActivate, this);
        this.getMainFrame().fireEvent('activate');
    },
    
    onShowUser: function(user) {
        var me = this;
        me.getHomePage().fireEvent('showUser', user);
        me.getMainFrame().setActiveItem(0);
        me.getMainFrame().un('activate', this.onActivate,this);
        me.getSuperMain().setActiveItem(0);
        me.getMainFrame().on('activate', this.onActivate,this);
    },
        
    onShowPost: function(post) {
        var me = this;
        me.getPostPage().fireEvent('showPost', post);
        me.getMainFrame().setActiveItem(1);
        me.getMainFrame().un('activate', this.onActivate);
        me.getSuperMain().setActiveItem(0);
    },
    
    onActivate: function () {
        console.log('homeController onActivate');
        var loginSessionStore = Ext.getStore('LogInSession').getData();
        sessionStore = loginSessionStore.items[0].data;
        
        this.getMainFrame().fireEvent('showUser', sessionStore.connectid);
    },
    
    /*onScrollEnd: function(scroller, x, y) {
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
    },*/
});