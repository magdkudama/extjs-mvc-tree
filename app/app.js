Ext.Loader.setConfig({
	enabled: true
});

Ext.application({
	name: 'TreeSample',

	autoCreateViewport: true,

	stores: ['Localidades'],
	controllers: ['Localidades']
});