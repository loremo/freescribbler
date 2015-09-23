Ext.define('FreescribbleApp.view.PicProcessing', {
    extend: 'Ext.Panel',
    requires: [
        'Ext.ux.CanvasDraw'
    ],
    xtype: 'picprocessing',
    config: {
        scrollable: false,
        styleHtmlContent: true,
        imageProportion: 1,
        screenProportion: 1,
        pic: null,
        cls: 'picProcessingWrapper',
        items: [
            {
                itemId: 'startPic',
                cls: 'startPicCls',
                hidden: false,
            },
            {
                xtype: 'canvasdraw',
                cls: 'canvasWrapperCls',
            },
            {
                itemId: 'endPic',
                cls: 'endPicCls',
                hidden: true,
            },
            {
                xtype: 'button',
                itemId: 'picCutBtn',
                text: 'Обрезать',
                cls: 'picCutBtnCls'
            },
            {
                xtype: 'button',
                itemId: 'picResetBtn',
                text: 'Вернуть',
                cls: 'picResetBtnCls'
            },
            {
                xtype: 'button',
                itemId: 'picSubmitBtn',
                text: 'Отправить',
                cls: 'picSubmitBtnCls'
            },
        ]
    },
});
