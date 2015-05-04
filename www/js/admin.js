$(function () {
	$.nette.init();


	var toolbars = [
		['Format'],
		['Undo', 'Redo'],
		['Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat'],
		['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord'],
		['Link', 'Unlink', 'Anchor'],
		['Image', 'Youtube', 'Table', 'HorizontalRule', 'SpecialChar', 'PageBreak', 'CreateDiv'],
		['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'],
		['UIColor'],
		['Maximize']
	];

	$('textarea.allow-cke').ckeditor({
		extraPlugins: 'autogrow,youtube,floating-tools,toolbar,button,maximize',
		floatingtools: 'Basic',
		floatingtools_Basic: toolbars,
		toolbar: toolbars,
		entities_latin: false,
		contentsCss: '/css/ckeditorContent.css',
		removeAttributes: 'class,style,lang,width,height,align,hspace,valign',
		allowedContent: true,
		filebrowserBrowseUrl: '/kcfinder/browse.php?type=files',
		filebrowserImageBrowseUrl: '/kcfinder/browse.php?type=images',
		filebrowserFlashBrowseUrl: '/kcfinder/browse.php?type=flash',
		filebrowserUploadUrl: '/kcfinder/upload.php?type=files',
		filebrowserImageUploadUrl: '/kcfinder/upload.php?type=images',
		filebrowserFlashUploadUrl: '/kcfinder/upload.php?type=flash'
	});
});
