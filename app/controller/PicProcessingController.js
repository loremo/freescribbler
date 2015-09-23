Ext.define("FreescribbleApp.controller.PicProcessingController", {
    extend: "Ext.app.Controller",requires: [
        'Ext.ux.CanvasDraw'
    ],
    config: {
        initial : false,
        fullyLoaded: false,
        loading: false,
        scroller: true,
        refs: {
            picProcessing   : 'picprocessing',
            newPost         : 'newpost', 
            startPic        : '[itemId = startPic]',
            endPic          : '[itemId = endPic]',
            picCut          : '[itemId = picCutBtn]',
            picReset        : '[itemId = picResetBtn]',
            picSubmit       : '[itemId = picSubmitBtn]',
            canvas          : 'canvasdraw',
        },
        
        control: {
            picprocessing: {
                prepair: 'onPrepair',
            },
            canvas: {
                initialize: 'onInitialize'
            },
            picCut: {
                tap: 'onPicCutTap'
            },
            picReset: {
                tap: 'onPicResetTap'
            },
            picSubmit: {
                tap: 'onPicSubmitTap'
            },
        },
    },
    pic: null,
    picTop: 0,
    picLeft: 0,
    
    picOriginWidth: 0,
    picOriginHeight: 0,
    picWidth: 0,
    picHeight: 0,
    scaleFactor: 1,
    
    endPic: null,
    endPicWidth: 0,
    endPicHeight: 0,
    endPicOriginWidth: 0,
    endPicOriginHeight: 0,
    
    init: function () {
    	console.log('INIT from EventController');
        //this.setInitial(true);
    },
    	 
    launch: function () {
        console.log('LAUNCH from EventController');
    },
        
    onPrepair: function() {
        var me = this,
            canvas = me.getCanvas(),                          //конвас с прорисовкой прямоугольником
            img = me.getStartPic().element.down('img'),       //стартовая картинка     
            picCut = me.getPicCut(),        //кнопка обрезать
            picReset = me.getPicReset(),    //кнопка обратно
            picSubmit = me.getPicSubmit(),  //кнопка отправить
            startPic = me.getStartPic(),    //див внутри которого стартовая картинка
            endPic = me.getEndPic(),        //див внутри которого конечная картинка
            dimension = me.getPicProcessing().element.getWidth(); //ширина экрана аппарата
        me.endPic = me.pic = img.dom.src;   //адрес стартовой и конечной картинки. перенимается из ресурса стартовой картинки.
        console.log(me.pic);
        picCut.setStyle('top', dimension+'px');         //расположение кнопки обрезать
        picReset.setStyle('top', 20+dimension+'px');    //расположение кнопки обратно
        picSubmit.setStyle('top', 40+dimension+'px');   //расположение кнопки отправить
        
        me.endPicOriginWidth = me.picOriginWidth = img.getWidth();      //настоящая ширина картинки для начальной и конечной картинки
        me.endPicOriginHeight = me.picOriginHeight = img.getHeight();   //настоящая высота картинки для начальной и конечной картинки
        
        startPic.setWidth(dimension);   //установка ширины дива со стартовой картинкой на уровне ширины экрана
        startPic.setHeight(dimension);  //установка высоты дива со стартовой картинкой на уровне ширины экрана
        endPic.setWidth(dimension);     //установка ширины дива с конечной картинкой на уровне ширины экрана
        endPic.setHeight(dimension);    //установка высоты дива с конечной картинкой на уровне ширины экрана

        var imgProportion = me.getPicProcessing().getImageProportion();
        
        var imgCanvas = document.createElement("canvas"),
            imgContext = imgCanvas.getContext("2d");
    
        imgCanvas.width = me.endPicOriginWidth;
        imgCanvas.height = me.endPicOriginHeight;
        if (imgProportion > 1) {
            if (imgCanvas.width > 1200) me.endPicOriginWidth = imgCanvas.width = 1200;
            me.endPicOriginHeight = imgCanvas.height = imgCanvas.width / imgProportion;
        }
        else {
            if (imgCanvas.height > 1200) me.endPicOriginHeight = imgCanvas.height = 1200;
            me.endPicOriginWidth = imgCanvas.width = imgCanvas.height * imgProportion;
        }
        imgContext.drawImage(img.dom, 0, 0, imgCanvas.width, imgCanvas.height, 0, 0, imgCanvas.width, imgCanvas.height);
        me.endPic = imgCanvas.toDataURL("image/jpeg");

        if (imgProportion > 1) {  // если картинка горизонтальная
            img.setWidth(dimension);  //скалируем картинку по ширине
            me.scaleFactor = dimension / me.picOriginWidth; //высчитываем фактор скалирования
            me.picLeft = 0;                                 //рассчет левого отступа канваса
            me.picWidth = dimension;                        //рассчет ширина канваса
            me.picHeight = dimension / imgProportion;       //рассчет высоты канваса
            me.picTop = (dimension - me.picHeight) / 2;     //рассчет верхнего отступа канваса
            img.setStyle('padding', me.picTop+'px 0');
            canvas.element.setStyle('left', '1px');
            canvas.element.setStyle('top', me.picTop+'px');
            canvas.setSize(me.picWidth, me.picHeight);
        }
        else {                  // если картинка вертикальная или квадратная
            img.setHeight(dimension); //скалируем картинку по высоте
            me.scaleFactor = dimension / me.picOriginHeight; //высчитываем фактор скалирования
            me.picTop = 0;                                  //рассчет верхнего отступа канваса
            me.picHeight = dimension;                       //рассчет высоты канваса
            me.picWidth = dimension * imgProportion;        //рассчет ширины канваса
            me.picLeft = (dimension - me.picWidth) / 2;     //рассчет левого отступа канваса
            img.setStyle('padding', '0 '+me.picLeft+'px');
            canvas.element.setStyle('top', '1px');
            canvas.element.setStyle('left', me.picLeft+'px');
            canvas.setSize(me.picWidth, me.picHeight);
        }
        console.log(me.scaleFactor + '/' + dimension + '/' + me.picOriginWidth + '/' + me.picOriginHeight);
        
    },
        
    onPicCutTap: function() {
        var me = this,
            img = new Image(),
            canvas = me.getCanvas(),
            imgCanvas = document.createElement("canvas"),
            imgContext = imgCanvas.getContext("2d"),
            dimension = me.getPicProcessing().element.getWidth();
            img.onload = function() {
                me.endPicOriginWidth = imgCanvas.width = canvas.getCWidth()/me.scaleFactor;
                me.endPicOriginHeight = imgCanvas.height = canvas.getCHeight()/me.scaleFactor;
                var newProportion = (canvas.getCWidth()/me.scaleFactor) / (canvas.getCHeight()/me.scaleFactor);
                if (newProportion > 1) {
                    if (canvas.getCWidth()/me.scaleFactor > 1200) me.endPicOriginWidth = imgCanvas.width = 1200;
                    else me.endPicOriginWidth = imgCanvas.width = canvas.getCWidth()/me.scaleFactor;
                    me.endPicOriginHeight = imgCanvas.height = imgCanvas.width / newProportion;
                }
                else {
                    if (canvas.getCHeight()/me.scaleFactor > 1200) me.endPicOriginHeight = imgCanvas.height = 1200;
                    else me.endPicOriginHeight = imgCanvas.height = canvas.getCHeight()/me.scaleFactor;
                    me.endPicOriginWidth = imgCanvas.width = imgCanvas.height * newProportion;
                }
                imgContext.drawImage(img, canvas.getCX()/me.scaleFactor, canvas.getCY()/me.scaleFactor,
                                          imgCanvas.width, imgCanvas.height, 0, 0, imgCanvas.width, imgCanvas.height);
                
                me.endPic = imgCanvas.toDataURL("image/jpeg");
                me.getEndPic().setHtml('<img src="' + imgCanvas.toDataURL("image/jpeg") + '">');
                me.getEndPic().show();
                me.getStartPic().hide();
                canvas.hide();
                
                var image = me.getEndPic().element.down('img');
                var newImgProportion = imgCanvas.width / imgCanvas.height;
                image.setWidth(dimension);
                
                if (newImgProportion > 1) {
                    me.endPicHeight = dimension / newImgProportion;
                    me.endPicTop = (dimension - me.endPicHeight) / 2;
                    image.setStyle('padding', me.endPicTop+'px 0');
                }
                else {
                    me.endPicWidth = dimension * newImgProportion;
                    me.endPicLeft = (dimension - me.endPicWidth) / 2;
                    image.setStyle('padding', '0 '+me.endPicLeft+'px');
                }
            }
            img.src = me.getPicProcessing().getPic();
    },
        
    onPicResetTap: function() {
        var me = this,
            canvas = me.getCanvas();
            me.endPic = me.pic;
            me.endPicOriginWidth = me.picOriginWidth;
            me.endPicOriginHeight = me.picOriginHeight;

            me.getEndPic().hide();
            me.getStartPic().show();
            canvas.show();
    },
        
    onPicSubmitTap: function() {
        var me = this,
            canvas = me.getCanvas();
    
            me.getEndPic().hide();
            me.getStartPic().show();
            canvas.show();
            me.getNewPost().fireEvent('addPic', me.endPic, me.endPicOriginWidth, me.endPicOriginHeight);
    },
        
    onInitialize: function() {
        var me = this,
            loginSessionStore = Ext.getStore('LogInSession').getData(),
            canvas = me.getCanvas();
        canvas.setSize(me.picWidth, me.picHeight);
    },

    submitPostTap: function(event) {
        console.log('start submitPostTap');
        var me = this,
            loginSessionStore = Ext.getStore('LogInSession').getData();
        sessionStore = loginSessionStore.items[0].data;

        Ext.Ajax.request({
            url: 'http://freescribbler.com/test.php',
            method: 'POST',
            params: {
                act: 'CREATEPOST',
                connectid: sessionStore.connectid,
                token: sessionStore.token,
                clientcode: 123123123,
                pictures: me.files,
                text: me.getTextInput().getValue(),
            },
            success: function(response){
                var text = response.responseText;
                // process server response here
            }
        });
    },
    
    
});