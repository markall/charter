// JavaScript Document
<!--//--><![CDATA[//><!--


	tinyMCE.init({
		// General options
		mode : "textareas",
		theme : "advanced",

		plugins : "safari,pagebreak,layer,table,advhr,advimage,advlink,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",

		// Theme options
		theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,forecolor,backcolor",
		theme_advanced_buttons2 :  "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist|,outdent,indent,blockquote,link,unlink,anchor,image,help,code,|fullscreen",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : true,

		// Example content CSS (should be your site CSS)
	//	content_css : "styles/mdcc-styles.css",

		// Drop lists for link/image/media/template dialogs
		template_external_list_url : "lists/template_list.js",
		external_link_list_url : "lists/link_list.js",
		external_image_list_url : "lists/image_list.js",
		media_external_list_url : "lists/media_list.js",
		width: "300px",
		
		// Replace values for the template plugin
		template_replace_values : {
			username : "Some User",
			staffid : "991234"
		}
	});


//--><!]]>