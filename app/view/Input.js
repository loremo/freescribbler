Ext.define('FreescribbleApp.view.Input', {
    extend: 'Ext.Panel',
    requires: [
        'Ext.form.Panel',
        'Ext.Button',
        'Ext.form.FieldSet',
        'Ext.field.Text',
        'Ext.field.File',
        'Ext.Toolbar',
        'Ext.ProgressIndicator'
    ],
    xtype: 'inputpage',
    config: {
        styleHtmlContent: true,
        items:[
            {
                xtype: "filefield",
                itemId: 'fileInputField',
                label: "Select image(s):",
                name: "photos",
                accept:"image",
                hidden: true,
                /*listeners: {
                    change: function (button, newValue, oldValue, eOpts) {
                        var input = Ext.Viewport.down("filefield").getComponent().input;
                        console.log(URL.createObjectURL(input.dom.files[0]));
                    }
                }*/
            },
            {
                xtype: 'panel',
                itemId: 'picsInputWrapper',
            },
            {
                xtype: "textareafield",
                itemId: 'textInputField',
                placeholder: 'Введие текст...',
            },
            {
                xtype: 'button',
                text: 'Отправить',
                itemId: 'submitPostBtn',
                ui: 'confirm'
            }
        ]
    },
    
});
