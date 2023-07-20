jQuery('.mgbutton.moove-gdpr-infobar-allow-all, .mgbutton.moove-gdpr-modal-save-settings').on('click',function(){
	setTimeout(function(){
	var cookiedata = JSON.parse(getCookie('moove_gdpr_popup'));
	if(cookiedata.strict == 1) {
	dataLayer.push({'cookieStrict':'granted'});
	} else{
		dataLayer.push({'cookieStrict':'denied'});
	}
	if( cookiedata.thirdparty == 1 ) {
		dataLayer.push({'cookieThirdparty':'granted'});
		dataLayer.push({'event':'cookieThirdparty-access-granted'});
	} else{
		dataLayer.push({'cookieThirdparty':'denied'});
	}
	if( cookiedata.advanced == 1 ) {
		dataLayer.push({'cookieAdvanced':'granted'});
	} else{
		dataLayer.push({'cookieAdvanced':'denied'});
	}
},3000);
});
