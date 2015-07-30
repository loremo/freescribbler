Ext.define("FreescribbleApp.controller.LoginController", {
    extend: "Ext.app.Controller",
    
    config: {
    	refs: {
            loginView: 'loginview',
            logout: 'titlebarview'
        },
        control: {
            loginView: {
                signInCommand: 'onSignInCommand'
            },
            logout: {
                signOutCommand: 'onSignOutCommand'
            }
        },
    },

init: function () {
   	 console.log('INIT from LoginController');
   	 },

launch: function () {
   	 console.log('LAUNCH from LoginController');
   	 },
         
onSignInCommand: function (view, username, password) {

    console.log('Username: ' + username + '\n' + 'Password: ' + password);

    var me = this,
        loginView = me.getLoginView(),
        login = {};

    if (username.length === 0 || password.length === 0) {

        loginView.showSignInFailedMessage('Please enter your username and password.');
        return;
    }
    
    loginView.setMasked({
        xtype: 'loadmask',
        message: 'Signing In...'
    });

    Ext.Ajax.request({
        url: 'http://freescribbler.com/test.php',
        method: 'post',
        params: {
            act: 'LOGIN',
            username: username,
            userpassword: password,
            clientcode: 123123123
        },
        success: function (response) {
            var loginResponse = Ext.JSON.decode(response.responseText);
            if (loginResponse.success) {
                login.connectid = loginResponse.connectid;
                login.token = loginResponse.token;
                console.log('Username: ' + login.connectid + '\n' + 'Password: ' + login.token);
                Ext.getStore('LogInSession').add(login)
                Ext.getStore('LogInLocal').add(login);
                    // The server will send a token that can be used throughout the app to confirm that the user is authenticated.
                Ext.Viewport.animateActiveItem(Ext.create('FreescribbleApp.view.Main2'), {type: 'slide', direction: 'left', duration: 400}) ;     //Just simulating success.
            } else {
                me.signInFailure(loginResponse.answer);
            }
        },
        failure: function (response) {
            me.sessionToken = null;
            me.signInFailure('Login failed. Please try again later.');
        }
    });
},
onSignOutCommand: function (view) {
    console.log('start onSignOffCommand ' + localStorage.getItem('token') + ' / ' +  localStorage.getItem('userId'));
    var me = this,
        loginSessionStore = Ext.getStore('LogInSession').getData();
    sessionStore = loginSessionStore.items[0].data;
    Ext.Ajax.request({
        url: 'http://freescribbler.com/test.php',
        method: 'post',
        params: {
            act: 'LOGOUT',
            connectid: sessionStore.connectid,
            token: sessionStore.token,
            clientcode: 123123123
        },
        success: function (response) {
            var logoffResponse = Ext.JSON.decode(response.responseText);
            if (logoffResponse.success) {
                Ext.Viewport.animateActiveItem(this.getLoginView(), {type: 'slide', direction: 'right', duration: 400});
            }
        }
    });
    Ext.getStore('LogInLocal').removeAll();
    Ext.getStore('LogInSession').removeAll();
    console.log('close onSignOffCommand ' + localStorage.getItem('token') + ' / ' +  localStorage.getItem('userId'))
},
signInSuccess: function () {
    console.log('Signed in.');
    var loginView = this.getLoginView();
    var mainMenuView = Ext.create('FreescribbleApp.view.Main2');
    var me = this,
        loginSessionStore = Ext.getStore('LogInSession').getData();
    sessionStore = loginSessionStore.items[0].data;
    loginView.setMasked(false);
    
    var myProfileStore = new Ext.data.Store({
        model: 'User',
        proxy: {
            type: 'ajax',
            method: 'POST',
            url: 'http://freescribbler.com/test.php',
            params: {
                act: 'GETUSER',
                connectid: sessionStore.connectid,
                token: sessionStore.token,
                clientcode: 123123123,
                userid: sessionStore.connectid
            },
            reader: {
                type: 'json'
            }
        },
        autoLoad: true,
    });
    
    myProfileStore.load();
    Ext.Viewport.animateActiveItem(mainMenuView, {type: 'slide', direction: 'left', duration: 400}) ;
},
signInFailure: function (message) {
    var loginView = this.getLoginView();
    loginView.showSignInFailedMessage(message);
    loginView.setMasked(false);
},
registerBtnTaped: function (view, username, password){
    console.log('registerBtnTaped');
    var mainMenuView = Ext.create('FreescribbleApp.view.Main');
    Ext.Viewport.add(mainMenuView);
    Ext.Viewport.animateActiveItem(mainMenuView, {type: 'flip', direction: 'left'}) ;
},   	
   	
   	
onViewDeactivate: function (view) {
    console.log('loginView DEACTIVATED');
    Ext.defer(function () {
        view.destroy();
        Ext.Viewport.remove(view);

    }, 500);
},
/*
onSignInCommand: function (view, username, password) {

    console.log('Username: ' + username + '\n' + 'Password: ' + password);

    var me = this,
        loginView = me.getLoginView();

    if (username.length === 0 || password.length === 0) {

        loginView.showSignInFailedMessage('Please enter your username and password.');
        return;
    }

    loginView.setMasked({
        xtype: 'loadmask',
        message: 'Signing In...'
    });

    Ext.Ajax.request({
        url: 'mockdata/login.php',
        method: 'post',
        params: {
            user: username,
            pwd: password
        },
        success: function (response) {

            var loginResponse = Ext.JSON.decode(response.responseText);

            if (loginResponse.success === "true") {
                // The server will send a token that can be used throughout the app to confirm that the user is authenticated.
                me.sessionToken = loginResponse.sessionToken;
                me.signInSuccess();     //Just simulating success.
            } else {
                me.signInFailure(loginResponse.message);
            }
        },
        failure: function (response) {
            me.sessionToken = null;
            me.signInFailure('Login failed. Please try again later.');
        }
    });
},
showSignInFailedMessage: function (message) {
    var label = this.down('#signInFailedLabel');
    label.setHtml(message);
    label.show();
},
signInFailure: function (message) {
    var loginView = this.getLoginView();
    loginView.showSignInFailedMessage(message);
    loginView.setMasked(false);
},
signInSuccess: function () {
    console.log('Signed in.');
    var loginView = this.getLoginView();
    mainMenuView = this.getMainMenuView();
    loginView.setMasked(false);

    Ext.Viewport.animateActiveItem(mainMenuView, this.getSlideLeftTransition());
}
*/
});