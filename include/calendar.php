
<script language="javascript" src="<?=SITE_URL;?>/admin/include/popcalendar.js"></script>
<script language="JavaScript">
function DeleteImage(a_preview,a_delete,is_image_deleted)
{

	obj=document.getElementById(a_preview);
	var displayvalue = obj.style.display;
	if(displayvalue === "none")
	{
		obj.style.display='';
		obj=document.getElementById(is_image_deleted);
		obj.value='no';
		obj=document.getElementById(a_delete);
		obj.innerHTML="[Delete]";
	}
	else
	{
		obj.style.display= 'none';
		obj=document.getElementById(is_image_deleted);
		obj.value='yes';
		obj=document.getElementById(a_delete);
		obj.innerHTML="[Undo Delete]";
	}
}
</script>

<script language="JavaScript">
// Set the horizontal and vertical position for the popup

PositionX = 100;
PositionY = 100;

// Set these value approximately 20 pixels greater than the
// size of the largest image to be used (needed for Netscape)

defaultWidth  = 500;
defaultHeight = 500;

// Set autoclose true to have the window close automatically
// Set autoclose false to allow multiple popup windows

var AutoClose = true;

// Do not edit below this line...
// ================================
if (parseInt(navigator.appVersion.charAt(0))>=4){
var isNN=(navigator.appName==="Netscape")?1:0;
var isIE=(navigator.appName.indexOf("Microsoft")!==-1)?1:0;}
var optNN='scrollbars=no,width='+defaultWidth+',height='+defaultHeight+',left='+PositionX+',top='+PositionY;
var optIE='scrollbars=no,width=150,height=100,left='+PositionX+',top='+PositionY;
function popImage(imageURL,imageTitle){
if (isNN){imgWin=window.open('about:blank','',optNN);}
if (isIE){imgWin=window.open('about:blank','',optIE);}
with (imgWin.document){
writeln('<html><head><title>Loading...</title><style>body{margin:0;}</style>');writeln('<sc'+'ript>');
writeln('var isNN,isIE;');writeln('if (parseInt(navigator.appVersion.charAt(0))>=4){');
writeln('isNN=(navigator.appName=="Netscape")?1:0;');writeln('isIE=(navigator.appName.indexOf("Microsoft")!=-1)?1:0;}');
writeln('function reSizeToImage(){');writeln('if (isIE){');writeln('window.resizeTo(100,100);');
writeln('width=100-(document.body.clientWidth-document.images[0].width);');
writeln('height=100-(document.body.clientHeight-document.images[0].height);');
writeln('window.resizeTo(width,height);}');writeln('if (isNN){');       
writeln('window.innerWidth=document.images["George"].width;');writeln('window.innerHeight=document.images["George"].height;}}');
writeln('function doTitle(){document.title="'+imageTitle+'";}');writeln('</sc'+'ript>');
if (!AutoClose) writeln('</head><body bgcolor=000000 scroll="no" onload="reSizeToImage();doTitle();self.focus()">')
else writeln('</head><body bgcolor=000000 scroll="no" onload="reSizeToImage(); resizable=true;doTitle();self.focus()" onblur="self.close()">');
writeln('<img name="George" src="'+imageURL+'" style="display:block"></body></html>');
close();		
}}
</script>
