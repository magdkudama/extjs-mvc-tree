Ext.define('TreeSample.view.Viewport', {
	extend: 'Ext.container.Viewport',
	layout: 'fit',
	requires: ['TreeSample.view.ArbolLocalidades'],

	initComponent: function() {

		this.items = {
			items: [{
				xtype: 'arbolLocalidades'
			}]
		}

		this.callParent();
	}
});