Ext.define('FreescribbleApp.view.Registration', {
    extend: 'Ext.form.Panel',
    xtype: 'registrationtab',
    requires: ['Ext.form.FieldSet', 'Ext.form.Password', 'Ext.Label', 'Ext.Img'],
    config: {
        title: 'Registration',
        items: [
            {
                xtype: 'fieldset',
                title: 'Your registration information:',
                items: [
                    {
                        xtype: 'emailfield',
                        placeHolder: 'Email',
                        itemId: 'userEmailTextField',
                        name: 'userEmailTextField',
                        required: true
                    },
                    {
                        xtype: 'textfield',
                        placeHolder: 'Username',
                        itemId: 'userNameTextField',
                        name: 'userNameTextField',
                        required: true
                    },
                    {
                        xtype: 'passwordfield',
                        placeHolder: 'Password',
                        itemId: 'passwordTextField',
                        name: 'passwordTextField',
                        required: true
                    }
                ]
            },
            {
                xtype: 'button',
                itemId: 'registerBtn',
                ui: 'action',
                padding: '10px',
                margin:'10px', 
                text: 'Register'
            }
        ]
    }
});