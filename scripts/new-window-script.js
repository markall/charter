
function fNewPDFWindows ()
{
	if (!document.getElementsByTagName) return false;
	var links = document.getElementsByTagName("a");
	for (var eleLink=0; eleLink < links.length; eleLink ++) {
		if (links[eleLink].href.indexOf('.pdf') !== -1) {
			links[eleLink].onclick =
			function() {
				window.open(this.href,'resizable,scrollbars');
				return false;
			}
		var img = document.createElement("img");
		img.setAttribute("src", "images/new-win-icon.gif");
		img.setAttribute("alt", "(opens in a new window)");
		links[eleLink].appendChild(img);		
		}
	}
}



function addLoadEvent(func)
{
	var oldonload = window.onload;
if (typeof window.onload != 'function') 
{ 
	window.onload = func;
} else {
	window.onload = function()
		{ oldonload();
		func();
		}
	}
}
addLoadEvent(fNewPDFWindows);