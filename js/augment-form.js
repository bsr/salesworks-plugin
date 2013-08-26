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
      
      // Populate Form
      fillFormValue(sw, 'swcity', city ,'.');
      fillFormValue(sw, 'swcountry', country,'.');
      fillFormValue(sw, 'swstate', state,'.');
      fillFormValue(sw, 'swregion', region,'.');;
      fillFormValue(sw, 'swlocation', location,'.');
      fillFormValue(sw, 'swzip', zip,'.');
      fillFormValue(sw, 'utmz_medium ', medium, '.');
      fillFormValue(sw, 'utmz_source ', source,'.');
      fillFormValue(sw, 'utmz_term ', term,'.');
      fillFormValue(sw, 'utmz_content ', content,'.');
      fillFormValue(sw, 'utmz_campaign ', campaign,'.');
      fillFormValue(sw, 'utmz_gclid ', csegment,'.');
	  
	  formData = readCookie('_formData');
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
	  }
      

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

