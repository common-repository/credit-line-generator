creditline = {

	baseClassName: '',
	editor: 'text', // Current options: 'text', 'visual'.

	showLineBox: function() {
		if ( creditline.mainForm !== undefined ) {
			creditline.mainForm.style.display = 'block';
			creditline.editor = 'text';
		}
	},

	showLineBoxMCE: function() {
		if ( creditline.mainForm !== undefined ) {
			creditline.mainForm.style.display = 'block';
			creditline.editor = 'visual';
		}
	},

	errMessage: function( name ) {
		return '';
	},

	getValue: function( myId, defaultValue ) {
		var myObj = document.getElementById( myId );

		if ( myObj !== null ) {
			if ( myObj.value !== undefined ) {
				return myObj.value;
			}
			else {
				return defaultValue;
			}
		}
		else {
			return defaultValue;
		}
	},

	submitLine: function() {
		var creditlineURL          = creditline.getValue( 'clg_url', '' ),
		    creditlinePhotographer = creditline.getValue( 'clg_photographer', '' ),
		    creditlineCCURL        = creditline.getValue( 'clg_ccurl', '' ),
		    creditlineFDLURL       = creditline.getValue( 'clg_fdlurl', '' ),
		    creditlineExtension    = creditline.getValue( 'clg_extension', '' ),
			gluePhoto              = creditline.getValue( 'clg_glue_photo', '' ),
			glueOf                 = creditline.getValue( 'clg_glue_subject', '' ),
			glueBy                 = creditline.getValue( 'clg_glue_author', '' ),
			glueCCL                = creditline.getValue( 'clg_glue_ccl', '' ),
			glueFDL                = creditline.getValue( 'clg_glue_fdl', '' ),
		    copyBuffer             = '',
		    url_in                 = '',
		    url_out                = '',
		    span_in                = '',
		    span_out               = '',
		    className              = '';

		if ( creditlineURL !== '' ) {
			url_in = '<a href="' + creditlineURL + '">';
			url_out = '</a>';
		}
		else {
			url_in = '';
			url_out = '';
		}

		if ( creditlineExtension !== '' ) {
			creditlineExt2 = glueOf + creditlineExtension.replace( /^[\\t\\n\\r ]*(.+)$/, "$1" ).replace( /^(.+)[\\t\\n\\r ]*$/, "$1" );
		}
		else {
			creditlineExt2 = '';
		}

		if ( url_in !== '' || creditlineExt2 !== '' || creditlinePhotographer !== '' ) {
			// Subject, e.g. Photo of a landscape, or: Photo.
			className = ( creditline.baseClassName === '' ) ? '' : 'subject';
			copyBuffer += creditline.markUp( ( url_in + gluePhoto + creditlineExt2 + url_out ), 'span', className);

			// Author.
			className =  ( creditline.baseClassName === '' )  ? '' : 'author';
			copyBuffer += glueBy + creditline.markUp(  creditlinePhotographer, 'span', className );
		}

		// License.
		className = ( creditline.baseClassName === '' ) ? '' : 'license';
		if ( creditlineCCURL !== '' ) {
			copyBuffer += '. ';
			copyBuffer += creditline.markUp( '<a href="' + creditlineCCURL + '">' + glueCCL + '</a>', 'span', className );
		}
		else {
			if ( creditlineFDLURL !== '' ) {
				copyBuffer += '. ' + glueFDL;
				copyBuffer += creditline.markUp( '<a href="' + creditlineFDLURL + '">GNU FDL</a>', 'span', className );
			}
		}

		if ( copyBuffer !== '' ) {
			copyBuffer += '.';
			copyBuffer = creditline.markUp( copyBuffer, 'span', creditline.baseClassName );
		}

		if ( 'text' === creditline.editor ) {
			var myObj = document.getElementById( 'content' );
			if ( myObj !== null  ) {
				// @todo Implement undo.
				// myObj.value += copyBuffer;
				QTags.insertContent( copyBuffer );
				creditline.cancelLine();
			}
		}
		else {
			if ( 'visual' === creditline.editor ) {
				tinyMCE.execCommand( 'mceInsertContent', true, copyBuffer );
				creditline.cancelLine();
			}
		}
	},

	markUp: function( inputMarkUp, elementType, className ) {
		if ( className === '' ) {
			return inputMarkUp;
		}
		var outputMarkUp = '<' + elementType + ' class="' + className + '">';
		outputMarkUp += inputMarkUp + '</' + elementType  + '>';
		return outputMarkUp;
	},

	cancelLine: function() {
		if ( creditline.mainForm !== null ) {
			creditline.mainForm.style.display = 'none';
		}
	},
};

jQuery(document).ready(function (){
	creditline.mainForm = document.getElementById('creditline');
	var baseClassName = creditline.mainForm.getAttribute( 'data-output_base_class' );
	if ( baseClassName !== '' && baseClassName !== null ) {
		creditline.baseClassName = baseClassName;
	}

	QTags.addButton( 'ed_credit', 'credit', creditline.showLineBox );
});
