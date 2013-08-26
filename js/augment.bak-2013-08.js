// ######################################### //
// validation, UTMZ scrape, GEO IP functionality
// Version 2.1 - June, 2013
// ######################################### //

// function to search inside arrays
Array.prototype.find = function(searchStr) {
  var returnArray = false;
  for (i=0; i<this.length; i++) {
    if (typeof(searchStr) == 'function') {
      if (searchStr.test(this[i])) {
        if (!returnArray) { returnArray = [] }
        returnArray.push(i);
      }
    } else {
      if (this[i]===searchStr) {
        if (!returnArray) { returnArray = [] }
        returnArray.push(i);
      }
    }
  }
  return returnArray;
} 

// Jquery
jQuery(document).ready(function () {

        // setup countries where form is NOT blocked
        var validcountries = [ 'US','GB','AU','NZ','IE','CA' ];
        var restrictMsg = "We're sorry this content is not available in your geographic location.";
        
        // setup variables used
        var sw = jQuery.noConflict();
        var hasloc = false;
        var city = '';
        var state = '';
        var country = '';
        var countrycode = '';
        var location = '';
        var region = "";
        var zip = "";
        
        // Which GEOIP database to use
        var bGoogle = false;       
        var bMaxMind =true;
		
		// Append iframe url or populate form?
        var bIframe = true;       
        var bform = false;

        // query GEOIP provider
        if (bGoogle)
         {
          if (google.loader.ClientLocation != null) {
              hasloc = true;
              city = google.loader.ClientLocation.address.city;
              region = google.loader.ClientLocation.address.region;
              location = google.loader.ClientLocation.latitude + ' ' + google.loader.ClientLocation.longitude;
          }
        }
        else
        {
            // maxmind
            // http://www.maxmind.com/app/javascript_city
            countrycode = geoip_country_code();
            country = geoip_country_name();
            city = geoip_city();
            state = geoip_region_name();
            region = geoip_region();
            location = geoip_latitude() + ' ' + geoip_longitude();
            zip = geoip_postal_code();
        }
      // Debug GEOIP
      //debugMsg = countrycode + ',' + country + ',' + city + ',' + state + ',' + region + ',' + location + ',' + zip;
      //alert(debugMsg);

        if (bform)
         {
			
			// Populate Form
			fillFormValue(sw, 'swcity', city ,'.');
			fillFormValue(sw, 'swcountry', country,'.');
			fillFormValue(sw, 'swstate', state,'.');
			fillFormValue(sw, 'swregion', region,'.');;
			fillFormValue(sw, 'swlocation', location,'.');
			fillFormValue(sw, 'swzip', zip,'.');
			fillFormValue(sw, 'utm_medium ', medium, '.');
			fillFormValue(sw, 'utm_source ', source,'.');
			fillFormValue(sw, 'utm_term ', term,'.');
			fillFormValue(sw, 'utm_content ', content,'.');
			fillFormValue(sw, 'utm_campaign ', campaign,'.');
			fillFormValue(sw, 'utm_gclid ', csegment,'.');
        }
        else 
        {

				/* original code
				  $("#salesfusion_variable").append('<IFRAME id="sfId_variable" width=400 height=750 >');
					// get the URL from the url attribute of div
					var urlDialog = $("#salesfusion_variable").attr('url');
					// var urlDialog = 'http://forms.omnivue.net/af2?LinkID=CH00095628eR00000037AD';
					var urlUTMZ = '&ga_source=' + source + '&ga_medium=' + medium + '&ga_term=' + term + '&ga_content=' + content + '&ga_campaign=' + campaign + '&ga_segment=' + csegment + '';	
					var url = urlDialog+''+urlUTMZ;
					$('iframe#sfId_variable').attr('src', url);
					$('iframe#sfId_variable').hide();
					$('iframe#sfId_variable').load(function() 
					{
						$("#salesfusion_offer_loading_variable").fadeOut(function () {
							 $('iframe#sfId_variable').show();
							$("#salesfusion_variable").fadeIn();
						});
						//callback(this);
					});
				*/
			
			
			// get the URL from the url attribute of div
			var urlDialog = sw("#pardotForm").attr('src');						
			// detect if ? exists in url already
			if(urlDialog) {
				if (urlDialog.indexOf("?") >= 0) {
					var urlJoin = '&';
				} else {
					var urlJoin = '?';
				}  
			}
			
			var urlUTMZ = 'utm_source=' + source + '&utm_medium=' + medium + '&utm_term=' + term + '&utm_content=' + content + '&utm_campaign=' + campaign + '&utm_gclid=' + csegment + '&city=' + city + '&state=' + state + '&country=' + country;	
			var urlFinal = urlDialog + urlJoin + urlUTMZ;			

			// check if there should be a redirect
			/*var redirect_to = sw("#pardotForm").attr('redirect_to');
			if(redirect_to) {
				var urlRedirect = '&redirect_to=' + redirect_to;
				var urlFinal = urlFinal + urlRedirect;
			}*/

			
		    sw('#pardotForm').attr('src', urlFinal);
			 
			// debug
			// dialog(urlDialog + '<br/>' + urlUTMZ + '<br/>' + url);
			

			
			
			
			
		
		}
	  
	  /* formData = readCookie('_formData');
	  if (formData !== false) {
		  formData = formData.split('_formData=');
		  formData = formData[1];
		  formData = formData.split(';');
		  formData = formData[0];
		  formData = formData.split(',');
		  for (var i = 0; i < formData.length; i++) {
			  formDataSplit = formData[i].split(':');
			  formFieldName = formDataSplit[0];
			  formFieldValue = formDataSplit[1];
			  fillFormValue(sw, formFieldName, formFieldValue,'#');
		  }
	  } */
      

      // Restrict Form Access to specific valid countrycodes
      // if a div with the id "swrestricted" exists on the page, then check for valid countries
      if (sw('#swrestricted').length > 0)
      {
        if(!validcountries.find(countrycode))
        {
            // hide content region
            // sw('.swvalidate').hide();
            // hide entire form
            sw('form').hide();
            // show custom restricted messsage
            // sw('#swrestricted').show();
            // // show default restriced message
            sw('#swrestricted').after("<span class=error>" + restrictMsg + "</span>");
        }
      }


	// Function to change other answers which are contigent on a primary question
	// Author: Vitaliy Isikov <visikov@gmail.com>
	//  So essentially, if user selects None in A (contingentPrimary) then the values for B and C (contingentSecondary) automatically switch to None (instantly) and the class “greyedOut” is added to B and C (contingentSecondary) 
	//  A will be “contingentPrimary”
	//  B and C will be “ContingentSecondary”
	// EAM
	

jQuery.noConflict();
});


function createCookie(data,days) {
	if (days) {
		var date = new Date();
		date.setTime(date.getTime()+(days*24*60*60*1000));
		var expires = "; expires="+date.toGMTString();
	}
	else var expires = "";
	document.cookie = data+expires+"; path=/";
}

function readCookie(name) {
	var ca = document.cookie;
	if (ca.search(name) !== -1) {
		return ca;
	}
	else {
		return false;
	}
}

function eraseCookie(name) {
	createCookie(name + "=",-1);
}

function fillFormValue(sw, classid, value, type) {
	value = unescape(value);
	value = value.replace(/\+/g, ' ');
	/* if (classid == 'swmedium') {
		var valueArray = new Array('', '-', '(none)', 'Cpc', 'organic', 'Banner', 'Email', 'referral', 'dm');
		for (var i = 0; i < valueArray.length; i++) {
			var checkValue = valueArray[i].toLowerCase();
			var sentValue = value.toString().toLowerCase();
			// pass as integer 
			/* if (sentValue == checkValue) {
				if (i != 0) {
					value = i - 1;
				}
				else {
					value = i;
				}
			}
			// pass as eloqua GUID
			if (sentValue == '') {
				value = '20F2589F-A014-DD11-A161-005056B21571';
			}
			if (sentValue == '-') {
				value = '20F2589F-A014-DD11-A161-005056B21571';
			}
			if (sentValue == '(none)') {
				value = '20F2589F-A014-DD11-A161-005056B21571';
			}
			if (sentValue == 'cpc') {
				value = 'A92F67F4-E4F4-E011-86ED-005056B23E4A';
			}
			if (sentValue == 'organic') {
				value = '1C1C96B5-A014-DD11-A161-005056B21571';
			}
			if (sentValue == 'banner') {
				value = '754E7425-61F6-E011-86ED-005056B23E4A';
			}
			if (sentValue == 'email') {
				value = '691FE085-747A-E011-9E98-005056B23E4A';
			}
			if (sentValue == 'referral') {
				value = '6AAE062E-61F6-E011-86ED-005056B23E4A';
			}
			if (sentValue == 'dm') {
				value = 'DD269F37-61F6-E011-86ED-005056B23E4A';
			}
			
		}
	} */
	
	/*if (sw('select' + type + classid + ' option[value="' + value  + '"]').length > 0 ) {
		sw('select' + type + classid + ' option[value="' + value  + '"]').attr('selected','selected');
	}
	else {
		// city wasn't in th select list so lets add it.      
		sw('select' + type + classid ).append(sw("<option></option>").attr("value", value).text(value).attr("selected", true));
	}*/
  
	// modify to look inside <p class="xxx"><input></p>
	if(sw('input','p' + type + classid).length > 0 ) {
		sw('input','p' + type + classid).val(value);		
	}
}

// ######################################### //
// padding hidden fields to pardot iframe
// http://www.pardot.com/faqs/forms/passing-a-redirect-url-to-a-form-as-a-hidden-field/
// Version 2.1 - June, 2013
// ######################################### //

/*

setTimeout(function(){
	function getUrlParameter(parameterName) {
		var queryString = document.URL;
		var parameterName = parameterName + "=";
		if (queryString.length > 0) {
			var begin = queryString.indexOf(parameterName);
		if (begin != -1) {
			begin += parameterName.length;
			var end = queryString.indexOf( "&" , begin);
			if (end == -1) {
				end = queryString.length
				}
			return unescape(queryString.substring(begin, end));
			}
		}
	return null;
}
 
var Url = getUrlParameter("requested_url");
if(Url != null) {
	top.location=Url;
} else {
	top.location="%%requested_url%%";
}},200);
*/

