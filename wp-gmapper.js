function getHttp()
{
    var xmlHttp;
    try
    {
        // Firefox, Opera 8.0+, Safari
        xmlHttp=new XMLHttpRequest();
    }
    catch (e)
    {
        // Internet Explorer
        try
        {
        xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
        }
        catch (e)
        {
            try
            {
                xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
            }
            catch (e)
            {
                alert("Your browser does not support AJAX!");
                return null;
            }
        }
    }

    return xmlHttp;
}

function processAjax( serverPage, object, getOrPost, str )
{
    var http = getHttp();
    if (getOrPost == "get")
    {
        http.open("GET", serverPage);
        http.onreadystatechange = function()
        {
            if (http.readyState == 4 && http.status == 200)
            {
                object.innerHTML = http.responseText;
                delete http;
                return true;
            }
            else if ( http.readyState == 4 )
                delete http;
        }
        http.send(null);
    }
    else
    {
        http.open("POST", serverPage, true);
        http.setRequestHeader("Content-Type", "application/x-www-form-urlencoded; charset=UTF-8");
        http.onreadystatechange = function()
        {
            if (http.readyState == 4 && http.status == 200)
            {
				var new_child = document.createElement("div");
				new_child.name="newdiv";
				new_child.id="newdiv";
                new_child.innerHTML = http.responseText;
				object.appendChild(new_child);
                delete http;
                return true;
            }
            else if ( http.readyState == 4 )
			{
				alert(http.status);
                delete http;
			}
        }
        http.send(str);
    }
    return false;
}

function submitPost(form_name)
{
	var frame = document.getElementById('content');
	if ( frame )
	{
		var new_child = document.getElementById("newdiv");
		if ( new_child )
		{
			if ( confirm("Any changes will be lost, are you sure?") )
				frame.removeChild(new_child);
			else
				return;
		}
		var post_var = document.getElementById(form_name);
		if ( post_var )
		{
			var post_val = post_var.value;
			var pserver = '/wp-content/plugins/wp-gmapper/content.php';
			processAjax( pserver, frame, "post", post_var);
		}
	}
}

function load()
{
	if (GBrowserIsCompatible())
	{
		var map = new GMap2(document.getElementById("wp-gmapper-map"));
		map.setCenter(new GLatLng(37.4419, -122.1419), 13);

		map.addControl(new GLargeMapControl());
		map.addControl(new GMapTypeControl());
//		map.setMapType(G_HYBRID_TYPE);
		addPoints(map);
		centerMap(map);
	}
}

function addPoints(map)
{
	for (i = 0; i < google_maps_images.length; i++)
	{
		var point = new GLatLng(google_maps_images[i][0], google_maps_images[i][1]);
		point.thumb = google_maps_images[i][2];
		point.desc = google_maps_images[i][3];
		google_maps_images[i][4] = point;
		map.addOverlay(new GMarker(point));
	}
}

function centerMap(map)
{
	var min_lat = 180; var min_lon = 180; var max_lat = -180; var max_lon = -180;

	for (i = 0; i < google_maps_images.length; i++)
	{
		if (google_maps_images[i][0] < min_lat || google_maps_images[i][1] < min_lon)
		{
			min_lat = google_maps_images[i][0];
			min_lon = google_maps_images[i][1];
			//alert("MIN lat: "+min_lat+" lon: "+min_lon);
		}
		//else
			//alert("nominchange");
		if (google_maps_images[i][0] > max_lat || google_maps_images[i][1] > max_lon)
		{
			max_lat = google_maps_images[i][0];
			max_lon = google_maps_images[i][1];
			//alert("MAX lat: "+max_lat+" lon: "+max_lon);
		}
		//else
			//alert("nomaxchange");
	}

	var low = new GLatLng(min_lat, min_lon);
	var high = new GLatLng(max_lat, max_lon);
	var myBounds = new GLatLngBounds( low, high );
	var myZoom = map.getBoundsZoomLevel(myBounds);
	map.setZoom(myZoom);
	map.setCenter(myBounds.getCenter());
}

