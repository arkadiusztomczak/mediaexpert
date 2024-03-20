Ext.application({
    name: 'Task 1',

    launch: function() {

        //Definicja modeli danych (Osobno User i History, który jest częścią User)

        Ext.define('UserModel', {
            extend: 'Ext.data.Model',
            fields: [{
                name: 'ID',
                type: 'int'
            }, {
                name: 'Name',
                type: 'string'
            }, {
                name: 'DateCreated',
                type: 'date'
            }, {
                name: 'Status',
                type: 'string'
            }, {
                name: 'DateLast',
                type: 'date'
            }, {
                name: 'History',
                type: 'auto'
            }]
        });

        Ext.define('UserHistoryModel', {
            extend: 'Ext.data.Model',
            fields: [{
                name: 'ID',
                type: 'int'
            }, {
                name: 'DocID',
                type: 'int'
            }, {
                name: 'Status',
                type: 'string'
            }, {
                name: 'Date',
                type: 'date'
            }]
        });

        //Wczytywanie danych z JSONa do Store UserModel.

        var userStore = Ext.create('Ext.data.Store', {
            model: 'UserModel',
            proxy: {
                type: 'ajax',
                url: './Data/Sample-201207.json',
                reader: {
                    type: 'json'
                }
            },
            /*data: [{
                ID: 1,
                Name: 'Imie',
                DateCreated: new Date(),
                Status: 'Active',
                DateLast: new Date()
            }],*/
            autoLoad: true
        });

        // Formularz pozwalający na edycję danych, który wyświetla się po kliknięciu w rekord tabeli

        var formPanel = Ext.create('Ext.form.Panel', {
            title: 'Data Form',
            bodyPadding: 5,
            width: 350,
            layout: 'anchor',
            defaults: {
                anchor: '100%'
            },
            buttons: [{
                text: 'Save',
                formBind: true,
                disabled: true,
                handler: function() {
                    var form = formPanel.getForm();
                    if (form.isValid()) {
                        var record = form.getRecord();
                        form.updateRecord(record);
                        userStore.sync();
                    }
                }
            }],
            items: [
                { xtype: 'textfield', name: 'Name', fieldLabel: 'Name' },
                { xtype: 'datefield', name: 'DateCreated', fieldLabel: 'Date Created' },
                { xtype: 'textfield', name: 'Status', fieldLabel: 'Status' }
            ],
        });

        //Okienko do wyświetlania formularza z edycją danych

        var editWindow = Ext.create('Ext.window.Window', {
            title: 'Edit',
            height: 300,
            width: 400,
            layout: 'fit',
            items: [formPanel],
            closeAction: 'hide' //Okienko jest ukrywane dla lepszej wydajności
        });

        // Grid wyświetlający tabelę z danymi

        var grid = Ext.create('Ext.grid.Panel', {
            store: userStore,
            width: 400,
            height: 200,
            title: 'Application Users',
            columns: [{ //Wyświetlanie przykładowych danych
                text: 'Name',
                width: 100,
                sortable: false,
                hideable: false,
                dataIndex: 'Name'
            }, {
                text: 'Created',
                width: 150,
                dataIndex: 'DateCreated'
            }, {
                text: 'Status',
                flex: 1,
                dataIndex: 'Status'
            }],
            listeners: {
                select: function(rowModel, record, index) {
                    formPanel.loadRecord(record);
                    editWindow.show();
                }
            }
        });

        Ext.create('Ext.container.Viewport', {
            layout: 'fit',
            items: [grid]
        });

    }
});