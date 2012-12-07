Ext.define('TreeSample.controller.Localidades', {
	extend: 'Ext.app.Controller',
	stores: ['Localidades'],
	views: ['ArbolLocalidades'],

	init: function() {
		this.control({
			'arbolLocalidades': {
				itemclick: this.nodeClick
			}
		});
	},

	nodeClick: function(view, record) {
		console.log(record);
	}

});