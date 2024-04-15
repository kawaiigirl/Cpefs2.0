	<style type="text/css">
	/* 
	General styles for this example page */
	
	#menuContainer{
		margin:0 auto;
		text-align:left;
		background-image: url(images/body_bg1.gif);
		background-repeat: repeat;
		padding-left:12px;
		padding-right:12px;
		padding-bottom:0;
	}
	
	#ads{
        width:730px;
        margin: 10px auto 0;
    }
	#dhtmlgoodies_menu1 img{
		border:0;
	}
	
	

	/* End general styles for this example page */
	/* General configuration CSS */
	
	#dhtmlgoodies_menu1 ul li ul{
		display:none;	/* Needed to display ok in Opera */
	}
		
	#dhtmlgoodies_menu1{	/* Menu object */
		visibility:hidden;	
	}
	#dhtmlgoodies_menu1 ul{
		margin:0;	/* No indent */
		padding:0;	/* No indent */
	}
	#dhtmlgoodies_menu1 li{
		list-style-type:none;	/* No bullets */
	}	
	#dhtmlgoodies_menu1 a{

		margin:0;
		padding:0;
	}
	/* End general configuration CSS */
	
	
	/* Cosmetic */
	
	/***********************************************************************
		CSS - MENU BLOCKS
	 	General rules for all menu blocks (group of sub items) 
	***********************************************************************/
	#dhtmlgoodies_menu1 ul{
		border:2px solid #000;
		background-color:#E2ECFE;
		padding:1px;
	}
		
	#dhtmlgoodies_menu1 ul.menuBlock1{	/* Menu bar - main menu items */
		padding:1px;
		background-image: url(images/body_bg1.gif);
		border:1px solid #FFF2F9;
		
		background-color:#E2ECFE;
		overflow:visible;
	}
	#dhtmlgoodies_menu1 ul.menuBlock2{	/* Menu bar - main menu items */
		padding:1px;
		border:1px solid #555;
	}
	
	/***********************************************************************
		CSS - MENU ITEMS
	 	Here, you could assign rules to the menu items at different depths.
	***********************************************************************/
	/* General rules for all menu items */
	#dhtmlgoodies_menu1 a{
		color: #000;
		text-decoration:none;
        padding-left:5px;
		padding-right:5px;
		font-weight:bold;

        /*! font-family:Verdana, Arial, Helvetica, sans-serif ; */
	
	}
	
	/*
	Main menu items 
	*/
	
	#dhtmlgoodies_menu1 .currentDepth1{
		padding-left:5px;
		padding-right:5px;
		border:1px solid #E2EBED;
	}
	#dhtmlgoodies_menu1 .currentDepth1over{
		padding-left:5px;
		padding-right:5px;
		border:1px solid #000;
		background-color:#E1234A;
		
		
	}
	#dhtmlgoodies_menu1 .currentDepth1 a{
		font-weight:bold;
        /*! font-family:Verdana, Arial, Helvetica, sans-serif ; */
		
	}
	#dhtmlgoodies_menu1 .currentDepth1over a{	/* Text rules */
		color:#FFF;
		
	
	}
	
	/* Sub menu depth 1 */
	#dhtmlgoodies_menu1 .currentDepth2{
		padding-right:2px;
		border:1px solid #FFF;
		background-color:#FFF2F9;
		font-size:13px;
		font-weight:bold;
		font-family:Verdana, Arial, Helvetica, sans-serif ;
	}
	#dhtmlgoodies_menu1 .currentDepth2over{
		padding-right:2px;
		background-color:#FFF2F9;
		font-size:13px;
		font-weight:bold;
	}	
	#dhtmlgoodies_menu1 .currentDepth2over a{	/* Text rules */
		color:#FF0000;
		font-size:13px;
		font-weight:bold;
	}	
	/* Sub menu depth 2 */
	#dhtmlgoodies_menu1 .currentDepth3{
		padding-right:2px;
		border:1px solid #FFF;
		font-size:12px;
		font-weight:bold;
	}
	#dhtmlgoodies_menu1 .currentDepth3over{
		padding-right:2px;
		background-color:#EDE3EB;
		border:1px solid #000;
		font-size:12px;
		font-weight:bold;
	}
	/* Sub menu depth 3 */
	#dhtmlgoodies_menu1 .currentDepth4{
		padding-right:2px;
		border:1px solid #FFF;
		font-size:12px;
		font-weight:bold;
	}
	#dhtmlgoodies_menu1 .currentDepth4over{
		padding-right:2px;
		background-color:#EBEDE3;
		border:1px solid #000;
		font-size:12px;
		font-weight:bold;
	}	
	
	
	#dhtmlgoodies_menu2 img{
		border:0;
	}
	
	

	/* End general styles for this example page */
	/* General configuration CSS */
	
	#dhtmlgoodies_menu2 ul li ul{
		display:none;	/* Needed to display ok in Opera */
	}
		
	#dhtmlgoodies_menu2{	/* Menu object */
		visibility:hidden;	
	}
	#dhtmlgoodies_menu2 ul{
		margin:0;	/* No indent */
		padding:0;	/* No indent */
	}
	#dhtmlgoodies_menu2 li{
		list-style-type:none;	/* No bullets */
	}	
	#dhtmlgoodies_menu2 a{

		margin:0;
		padding:0;
	}
	/* End general configuration CSS */
	
	
	/* Cosmetic */
	
	/***********************************************************************
		CSS - MENU BLOCKS
	 	General rules for all menu blocks (group of sub items) 
	***********************************************************************/
	#dhtmlgoodies_menu2 ul{
		border:2px solid #000;
		background-color:#FFF;
		padding:1px;
	}
		
	#dhtmlgoodies_menu2 ul.menuBlock1{	/* Menu bar - main menu items */
		padding:1px;
		background-image: url(images/body_bg1.gif);
		border:1px solid #FFF2F9;
		
		background-color:#FFDFEF;
		overflow:visible;
	}
	#dhtmlgoodies_menu2 ul.menuBlock2{	/* Menu bar - main menu items */
		padding:1px;
		border:1px solid #555;
	}
	
	/***********************************************************************
		CSS - MENU ITEMS
	 	Here, you could assign rules to the menu items at different depths.
	***********************************************************************/
	/* General rules for all menu items */
	#dhtmlgoodies_menu2 a{
		color: #000;
		text-decoration:none;
		padding-left:2px;
		padding-right:2px;
	}
	
	/*
	Main menu items 
	*/
	
	#dhtmlgoodies_menu2 .currentDepth1{
		padding-left:5px;
		padding-right:5px;
		border:1px solid #E2EBED;
	}
	#dhtmlgoodies_menu2 .currentDepth1over{
		padding-left:5px;
		padding-right:5px;
		border:1px solid #000;
		background-color:#E1234A;
		
		
	}
	#dhtmlgoodies_menu2 .currentDepth1 a{
		font-weight:bold;
		font-family:Verdana, Arial, Helvetica, sans-serif ;
		
	}
	#dhtmlgoodies_menu2 .currentDepth1over a{	/* Text rules */
		color:#FFF;
		font-weight:bold;
		font-size:14px;
		font-family:Verdana, Arial, Helvetica, sans-serif ;
    }
	
	/* Sub menu depth 1 */
	#dhtmlgoodies_menu2 .currentDepth2{
		padding-right:2px;
		border:1px solid #FFF;
		background-color:#FFF2F9;
		font-size:13px;
		font-weight:bold;
		font-family:Verdana, Arial, Helvetica, sans-serif ;
	}
	#dhtmlgoodies_menu2 .currentDepth2over{
		padding-right:2px;
		background-color:#FFF2F9;
		font-size:13px;
		font-weight:bold;
	}	
	#dhtmlgoodies_menu2 .currentDepth2over a{	/* Text rules */
		color:#FF0000;
		font-size:13px;
		font-weight:bold;
	}	
	/* Sub menu depth 2 */
	#dhtmlgoodies_menu2 .currentDepth3{
		padding-right:2px;
		border:1px solid #FFF;
		font-size:12px;
		font-weight:bold;
	}
	#dhtmlgoodies_menu2 .currentDepth3over{
		padding-right:2px;
		background-color:#EDE3EB;
		border:1px solid #000;
		font-size:12px;
		font-weight:bold;
	}
	/* Sub menu depth 3 */
	#dhtmlgoodies_menu2 .currentDepth4{
		padding-right:2px;
		border:1px solid #FFF;
		font-size:12px;
		font-weight:bold;
	}
	#dhtmlgoodies_menu2 .currentDepth4over{
		padding-right:2px;
		background-color:#EBEDE3;
		border:1px solid #000;
		font-size:12px;
		font-weight:bold;
	}	
	
	
	


	</style>
	<script type="text/javascript">
	
	/************************************************************************************************************
	(C) www.dhtmlgoodies.com, October 2005
	
	This is a script from www.dhtmlgoodies.com. You will find this and a lot of other scripts at our website.	
	
	Terms of use:
	You are free to use this script as long as the copyright message is kept intact. However, you may not
	redistribute, sell or repost it without our permission.
	
	Thank you!
	
	www.dhtmlgoodies.com
	Alf Magne Kalleland
	
	************************************************************************************************************/	
		
	let dhtmlgoodies_menuObj;	// Reference to the menu div
    let currentZIndex = 1000;
    let liIndex = 0;
    let visibleMenus = [];
    let activeMenuItem = false;
    let timeBeforeAutoHide = 1200; // Microseconds from mouse leaves menu to auto hide.
    let dhtmlgoodies_menu_arrow = 'images/arrow.gif';

    let MSIE = navigator.userAgent.indexOf('MSIE')>=0?true:false;
    let navigatorVersion = navigator.appVersion.replace(/.*?MSIE ([0-9]\.[0-9]).*/g,'$1')/1;
    let menuBlockArray = [];
    let menuParentOffsetLeft = false;
	function getTopPos(inputObj)
	{

        let returnValue = inputObj.offsetTop;
	  if(inputObj.tagName==='LI' && inputObj.parentNode.className==='menuBlock1'){
          let aTag = inputObj.getElementsByTagName('A')[0];
	  	if(aTag)returnValue += aTag.parentNode.offsetHeight;

	  }	  
	  while((inputObj = inputObj.offsetParent) != null)returnValue += inputObj.offsetTop;

	  return returnValue;
	}
	
	function getLeftPos(inputObj)
	{
        let returnValue = inputObj.offsetLeft;
	  while((inputObj = inputObj.offsetParent) != null)returnValue += inputObj.offsetLeft;
	  return returnValue;
	}
	
	function showHideSub()
	{

        let attr = this.parentNode.getAttribute('currentDepth');
		if(navigator.userAgent.indexOf('Opera')>=0){
			attr = this.parentNode.currentDepth;
		}
		
		this.className = 'currentDepth' + attr + 'over';
		
		if(activeMenuItem && activeMenuItem!==this){
			activeMenuItem.className=activeMenuItem.className.replace(/over/,'');
		}
		activeMenuItem = this;

        let numericIdThis = this.id.replace(/[^0-9]/g,'');
        let exceptionArray = [];
		// Showing sub item of this LI
        let sub = document.getElementById('subOf' + numericIdThis);
		if(sub){
			visibleMenus.push(sub);
			sub.style.display='';
			sub.parentNode.className = sub.parentNode.className + 'over';
			exceptionArray[sub.id] = true;
		}	
		
		// Showing parent items of this one

        let parent = this.parentNode;
		while(parent && parent.id && parent.tagName==='UL'){
			visibleMenus.push(parent);
			exceptionArray[parent.id] = true;
			parent.style.display='';

            let li = document.getElementById('dhtmlgoodies_listItem' + parent.id.replace(/[^0-9]/g,''));
			if(li.className.indexOf('over')<0)li.className = li.className + 'over';
			parent = li.parentNode;
			
		}

			
		hideMenuItems(exceptionArray);



	}

	function hideMenuItems(exceptionArray)
	{
		/*
		Hiding visible menu items
		*/
        let newVisibleMenuArray = [];
		for(let no=0;no<visibleMenus.length;no++){
			if(visibleMenus[no].className!=='menuBlock1' && visibleMenus[no].id){
				if(!exceptionArray[visibleMenus[no].id]){
                    let el = visibleMenus[no].getElementsByTagName('A')[0];
					visibleMenus[no].style.display = 'none';
                    let li = document.getElementById('dhtmlgoodies_listItem' + visibleMenus[no].id.replace(/[^0-9]/g,''));
					if(li.className.indexOf('over')>0)li.className = li.className.replace(/over/,'');
				}else{				
					newVisibleMenuArray.push(visibleMenus[no]);
				}
			}
		}		
		visibleMenus = newVisibleMenuArray;		
	}



    let menuActive = true;
    let hideTimer = 0;
	function mouseOverMenu()
	{
		menuActive = true;		
	}
	
	function mouseOutMenu()
	{
		menuActive = false;
		timerAutoHide();	
	}
	
	function timerAutoHide()
	{
		if(menuActive){
			hideTimer = 0;
			return;
		}
		
		if(hideTimer<timeBeforeAutoHide){
			hideTimer+=100;
			setTimeout('timerAutoHide()',99);
		}else{
			hideTimer = 0;
			autohideMenuItems();	
		}
	}
	
	function autohideMenuItems()
	{
		if(!menuActive){
			hideMenuItems([]);
			if(activeMenuItem)activeMenuItem.className=activeMenuItem.className.replace(/over/,'');		
		}
	}
	
	
	function initSubMenus(inputObj,initOffsetLeft,currentDepth)
	{
        let subUl = inputObj.getElementsByTagName('UL');
		if(subUl.length>0){
            let ul = subUl[0];
			
			ul.id = 'subOf' + inputObj.id.replace(/[^0-9]/g,'');
			ul.setAttribute('currentDepth' ,currentDepth);
			ul.currentDepth = currentDepth;
			ul.className='menuBlock' + currentDepth;
			ul.onmouseover = mouseOverMenu;
			ul.onmouseout = mouseOutMenu;
			currentZIndex+=1;
			ul.style.zIndex = currentZIndex;
			menuBlockArray.push(ul);
            let topPos = getTopPos(inputObj);
            let leftPos = getLeftPos(inputObj)/1 + initOffsetLeft/1;
			ul = dhtmlgoodies_menuObj.appendChild(ul);
			ul.style.position = 'absolute';
			ul.style.left = leftPos + 'px';
			ul.style.top = topPos + 'px';
            let li = ul.getElementsByTagName('LI')[0];

			while(li){
				if(li.tagName==='LI'){
					li.className='currentDepth' + currentDepth;					
					li.id = 'dhtmlgoodies_listItem' + liIndex;
					liIndex++;
                    let uls = li.getElementsByTagName('UL');
					li.onmouseover = showHideSub;

					if(uls.length>0){
                        let offsetToFunction = li.getElementsByTagName('A')[0].offsetWidth+2;
						if(navigatorVersion<6 && MSIE)offsetToFunction+=15;	// MSIE 5.x fix
						initSubMenus(li,offsetToFunction,(currentDepth+1));
					}	
					if(MSIE){
                        let a = li.getElementsByTagName('A')[0];
						a.style.width=li.offsetWidth+'px';
						a.style.display='block';
					}					
				}
				li = li.nextSibling;
			}
			ul.style.display = 'none';	

		}	
	}


	function resizeMenu()
	{
        let offsetParent = getLeftPos(dhtmlgoodies_menuObj);
		
		for(let no=0;no<menuBlockArray.length;no++){
            let leftPos = menuBlockArray[no].style.left.replace('px','')/1;
			menuBlockArray[no].style.left = leftPos + offsetParent - menuParentOffsetLeft + 'px';
		}
		menuParentOffsetLeft = offsetParent;
	}
	
	/* 
	Initializing menu 
	*/
	function initDhtmlGoodiesMenu(j)
	{
        let pkr = 'dhtmlgoodies_menu' + j;
		dhtmlgoodies_menuObj = document.getElementById(pkr);


        let aTags = dhtmlgoodies_menuObj.getElementsByTagName('A');
		for(let no=0;no<aTags.length;no++){

            let subUl = aTags[no].parentNode.getElementsByTagName('UL');
			if(subUl.length>0 && aTags[no].parentNode.parentNode.parentNode.id !== pkr){
                let img = document.createElement('IMG');
				img.src = dhtmlgoodies_menu_arrow;
				aTags[no].appendChild(img);				

			}

		}

        let mainMenu = dhtmlgoodies_menuObj.getElementsByTagName('UL')[0];
		mainMenu.className='menuBlock1';
		mainMenu.style.zIndex = currentZIndex;
		mainMenu.setAttribute('currentDepth' ,'1');
		mainMenu.currentDepth = '1';
		mainMenu.onmouseover = mouseOverMenu;
		mainMenu.onmouseout = mouseOutMenu;

        let mainMenuItemsArray = [];
        let mainMenuItem = mainMenu.getElementsByTagName('LI')[0];
		mainMenu.style.height = mainMenuItem.offsetHeight + 2 + 'px';
		while(mainMenuItem){
			
			mainMenuItem.className='currentDepth1';
			mainMenuItem.id = 'dhtmlgoodies_listItem' + liIndex;
			mainMenuItem.onmouseover = showHideSub;
			liIndex++;				
			if(mainMenuItem.tagName==='LI'){
				mainMenuItem.style.cssText = 'float:left;';	
				mainMenuItem.style.styleFloat = 'left';
				mainMenuItemsArray[mainMenuItemsArray.length] = mainMenuItem;
				initSubMenus(mainMenuItem,0,2);
			}			
			
			mainMenuItem = mainMenuItem.nextSibling;
			
		}

		for(let no=0;no<mainMenuItemsArray.length;no++){
			initSubMenus(mainMenuItemsArray[no],0,2);			
		}
		
		menuParentOffsetLeft = getLeftPos(dhtmlgoodies_menuObj);	
		window.onresize = resizeMenu;	
		dhtmlgoodies_menuObj.style.visibility = 'visible';	
	}
	
	</script>
