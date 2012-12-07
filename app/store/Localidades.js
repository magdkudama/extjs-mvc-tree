Ext.define('TreeSample.store.Localidades', {
	extend: 'Ext.data.TreeStore',
	proxy: {
		type: 'ajax',
		url: 'data/web/index.php/tree'
	},
	root: {
		text: 'España',
		id: 'root',
		expanded: false
	}
});