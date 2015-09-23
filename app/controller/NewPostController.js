Ext.define("FreescribbleApp.controller.NewPostController", {
    extend: "Ext.app.Controller",
    requires: ['FreescribbleApp.view.OnePost'],
    config: {
        refs: {
            inputPage           : 'inputpage', 
            picProcessing       : 'picprocessing',
            newPost             : 'newpost',
            mainFrame           : 'main2',
            picsInput           : '[itemId = picsInputWrapper]',
            textInput           : '[itemId = textInputField]',
            fileInput           : '[itemId = fileInputField]',
            submitBtn           : '[itemId = submitPostBtn]',
        },
        
        control: {
            newPost: {
                activate        : 'onActivate',
                process         : 'onProcess',
                addPic          : 'setPicture'
            },
            submitBtn: {
                tap             : 'onSubmitPostTap'
            },
        },
        picData: null,
    },
    files: [],
    widths: [],
    heights: [],
    picCount: 0,
    init: function () {
    	console.log('INIT from HomeController');
    },
    	 
    launch: function () {
        console.log('LAUNCH from HomeController');
    },
        
    onActivate: function() {
        console.log('start newPost onActivate');
        var me = this;
        me.getNewPost().setActiveItem(0);
        me.getFileInput().on('change', this.initPicture, this); //Step 2
        //me.getPicsInput().removeAll();
        files = [];
        var newPic = null;
        if (me.getPicsInput().getItems().length == 0) {
            newPic = Ext.create('Ext.Container',
            {
                cls: 'addPicCls',
            });
            newPic.element.on('tap', function (button, e, eOpts) {
                me.getFileInput().getComponent().input.dom.click();
            });
            me.getPicsInput().add(newPic);
            this.newPicInput = newPic;
        }
    },

    initPicture: function(event) {
        
        var file = event.getComponent().getFiles(),
                me = this
                newPicInput = this.newPicInput;
        //FIX for webkit
        window.URL = window.URL || window.webkitURL; //Step 3
        if (file.length === 1 && file[0].type.indexOf("image/") === 0) {
            newPicInput.element.clearListeners();
            newPicInput.element.on('tap', function (button, e, eOpts) {
                var items = me.getPicsInput().getInnerItems();
                var aktItem = Ext.getCmp(Ext.get(this).id);
                var index = items.indexOf(aktItem);
                me.files.splice(index,1);
                me.widths.splice(index,1);
                me.heights.splice(index,1);
                aktItem.destroy();
                me.picCount--;
            });
            
            me.getNewPost().fireEvent('process', URL.createObjectURL(file[0]));
            me.getFileInput().reset();
        }
        
    },

    setPicture: function(img, width, height) {
        
        var me = this
            newPicInput = this.newPicInput;
        //me.files.push(img.replace(/^data:image\/(png|jpg);base64,/, ""));
        me.files.push(img);
        me.widths.push(Math.floor(width));
        me.heights.push(Math.floor(height));
        newPicInput.element.setStyle('backgroundImage', 'url(' + img + ')');
        me.getNewPost().setActiveItem(0);
        me.picCount++;
        console.log(me.picCount);
        if (me.picCount < 10) {
            var newPic = Ext.create('Ext.Container',
            {
                cls: 'addPicCls',
            });
            newPic.element.on('tap', function (button, e, eOpts) {
                me.getFileInput().getComponent().input.dom.click();
            });
            me.getPicsInput().add(newPic);
            this.newPicInput = newPic;
        }
    },
        
    onProcess: function(pic) {
        var me = this;
        
        me.setPicData(pic);
        var processingCard = me.getPicProcessing();
        var image = new Image();
        image.onload = function() {
            me.getNewPost().setActiveItem(1);
            processingCard.down('#startPic').setWidth('');
            processingCard.down('#startPic').setHeight('');
            processingCard.down('#startPic').setHtml('<img src="' + pic + '">');
            processingCard.setPic(pic);
            processingCard.setImageProportion(this.width/this.height);
            processingCard.fireEvent('prepair', this);
        }
        image.src = pic;
    },
        
    onSubmitPostTap: function(pic) {
        var me = this,
            loginSessionStore = Ext.getStore('LogInSession').getData();
        sessionStore = loginSessionStore.items[0].data;
        Ext.Ajax.request({
            url: 'http://freescribbler.com/test.php',
            method: 'post',
            params: {
                act: 'CREATEPOST',
                connectid: sessionStore.connectid,
                token: sessionStore.token,
                clientcode: 123123123,
                posttext: encodeURIComponent(me.getTextInput().getValue()),
                postpicswidth: encodeURIComponent(Ext.encode(me.widths)),
                postpicsheight: encodeURIComponent(Ext.encode(me.heights)),
                postpics: encodeURIComponent(Ext.encode(me.files))
            },
            success: function (response) {
                me.files = [];
                me.widths = [];
                me.heights = [];
                me.picCount = 0;
                me.getPicsInput().removeAll();
                me.getMainFrame().setActiveItem(0);
            }
        });
    },
});