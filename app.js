/*
    This file is generated and updated by Sencha Cmd. You can edit this file as
    needed for your application, but these edits will have to be merged by
    Sencha Cmd when it performs code generation tasks such as generating new
    models, controllers or views and when running "sencha app upgrade".

    Ideally changes to this file would be limited and most work would be done
    in other places (such as Controllers). If Sencha Cmd cannot merge your
    changes and its generated code, it will produce a "merge conflict" that you
    will need to resolve manually.
*/

Ext.application({
    name: 'FreescribbleApp',

    requires: ['Ext.MessageBox', 'Ext.SegmentedButton', 'Ext.field.Search', 'Ext.field.Toggle', 'Ext.field.Email'],
    models: ['UserModel', 'UserLightModel', 'PostModel', 'CommentModel', 'PicModel', 'LogInCredential'],
    stores:['UserStore', 'PostStore', 'CommentStore', 'UserLightStore', 'LogInLocal', 'LogInSession'],
    views: ['Main2', 'Login', 
<<<<<<< HEAD
        'Home', 'Home2', 'News', 'Event', 
        'PostDataView', 'EventDataView', 'CommentDataView', 'UserLightDataView',
        'PostItem', 'CommentItem', 'UserLightItem', 'EventItem',
        'PostPage', 'UserInfoView', 'Titlebarview', 'OnePost'],
    controllers: [ 'LoginController', 'HomeController', 'Home2Controller', 'NewsController', 'EventController', 'PostPageController'],
=======
        'Home', 'Home2', 'News', 'Event', 'NewPost', 'Input', 'PicProcessing',
        'PostDataView', 'EventDataView', 'CommentDataView', 'UserLightDataView',
        'PostItem', 'CommentItem', 'UserLightItem', 'EventItem',
        'PostPage', 'UserInfoView', 'Titlebarview', 'OnePost'],
    controllers: [ 'LoginController', 'HomeController', 'Home2Controller', 'NewsController', 'EventController', 'PostPageController', 'NewPostController', 'PicProcessingController'],
>>>>>>> master

    icon: {
        '57': 'resources/icons/Icon.png',
        '72': 'resources/icons/Icon~ipad.png',
        '114': 'resources/icons/Icon@2x.png',
        '144': 'resources/icons/Icon~ipad@2x.png'
    },

    isIconPrecomposed: true,

    startupImage: {
        '320x460': 'resources/startup/320x460.jpg',
        '640x920': 'resources/startup/640x920.png',
        '768x1004': 'resources/startup/768x1004.png',
        '748x1024': 'resources/startup/748x1024.png',
        '1536x2008': 'resources/startup/1536x2008.png',
        '1496x2048': 'resources/startup/1496x2048.png'
    },

    launch: function() {
        // Destroy the #appLoadingIndicator element
        
        Ext.fly('appLoadingIndicator').destroy();
        var loginLocalStoreData = Ext.getStore('LogInLocal').getData(),
            loginSessionStore = Ext.getStore('LogInSession'),
            localStore,
            login = {};
    
        if (loginLocalStoreData.length) {
            // Fetching loginlocalstore data
            localStore = loginLocalStoreData.items[0].data;
            login.connectid = localStore.connectid;
            login.token = localStore.token;

            // Adding the loginlocalstore data into loginsessionstore
            loginSessionStore.add(login);
            console.log('token exists ');
            Ext.Viewport.add(Ext.create('FreescribbleApp.view.Main2'));
        }
        else {
            console.log('token not exists');
            Ext.Viewport.add(Ext.create('FreescribbleApp.view.Login'));
        }

       
      /*  if(Ext.browser.is.PhoneGap) {
            alert(device.uuid);
        } else {
            alert('Error: No PhoneGap.');
        }*/
    },

    onUpdated: function() {
        Ext.Msg.confirm(
            "Application Update",
            "This application has just successfully been updated to the latest version. Reload now?",
            function(buttonId) {
                if (buttonId === 'yes') {
                    window.location.reload();
                }
            }
        );
    }
});
