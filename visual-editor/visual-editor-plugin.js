/* TinyMCE plugin for WordPress Creditline Generator plug-in.
	Details on creating TinyMCE plugins at
		https://www.tiny.cloud/docs/general-configuration-guide/work-with-plugins
		https://www.tiny.cloud/docs/advanced/creating-a-plugin/
		http://qnimate.com/adding-buttons-to-wordpress-visual-editor/ 
*/
(function() {
	tinymce.create( 'tinymce.plugins.creditline_plugin', {
		getInfo: function() {
			return {
				longname:  'Creditline Generator Support for Editor',
				author:    'Branko Collin',
				authorurl: 'http://www.abeleto.nl',
				version:   '0.3',
			};
		},

		init: function(ed, url) {
			ed.addButton( 'creditline_button', {
				title:   'Credit the photographer',
				image:   url + '/../icon.svg',
				onclick: function () {
					creditline.showLineBoxMCE();
				},
			});
		},

		createControl: function (n, cm) {
			return null;
		}

	});

	// Adds the plugin class to the list of available TinyMCE plugins
	tinymce.PluginManager.add( 'creditline_plugin', tinymce.plugins.creditline_plugin );
})();
