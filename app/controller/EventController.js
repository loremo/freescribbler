Ext.define("FreescribbleApp.controller.EventController", {
    extend: "Ext.app.Controller",
    config: {
        initial : false,
        fullyLoaded: false,
        loading: false,
        scroller: true,
        refs: {
            eventPage: 'eventpage', 
            eventContent: '[itemId = eventContent]',
        },
        
        control: {
            eventPage: {
                activate: 'onShowEvents'
            },
        },
    },
    init: function () {
    	console.log('INIT from EventController');
        //this.setInitial(true);
    },
    	 
    launch: function () {
        console.log('LAUNCH from EventController');
    },
        
    onShowEvents: function() {
        console.log('start onShowEvents');
        var me = this,
            loginSessionStore = Ext.getStore('LogInSession').getData();
        sessionStore = loginSessionStore.items[0].data;
      
        var eventStage  = this.getEventContent();
        
        var eventStore = Ext.create('FreescribbleApp.store.EventStore', {
            params: {
                act: 'GETEVENTS',
                connectid: sessionStore.connectid,
                token: sessionStore.token,
                clientcode: 123123123
            }
        });
        eventStore.setStoreId('eventstore');
        var eventView = Ext.create('FreescribbleApp.view.EventDataView');
        
        eventStage.removeAll();
        eventStore.load();
        eventStore.on('load', function(){
            console.log('currentEventView');
            eventView.setStore(eventStore);
            eventStage.add(eventView);
            me.setScroller(me.getEventPage().getScrollable().getScroller());
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