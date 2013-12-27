<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml">
  <head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
    <title>Map</title>
    <script src="http://ditu.google.cn/maps?file=api&v=2.x&key=ABQIAAAAXpO2byQMRYmJvpy6AvMrhRRKlIpg4Ljew_mmRpSc0bPr-0luXBTmhX01XLol6KHNylhqcUQMuiQb3w&hl=zh-CN" type="text/javascript"></script>
  </head>

  <body>
      <div id="map_canvas" style="width: 500px; height: 300px"></div>
  </body>
    <script type="text/javascript">
    var map = null;
    var geocoder = null;
    function showAddress(address)
	{
		if (GBrowserIsCompatible()) 
		{
			map = new GMap2(document.getElementById("map_canvas"));
			//map.setCenter(new GLatLng(39.917, 116.397), 13);
			geocoder = new GClientGeocoder();
		}
		
      if (geocoder) {
        geocoder.getLatLng(
          address,
          function(point) {
            if (!point) {
              alert("Error: " + address);
            } else {
              map.setCenter(point, 13);
              var marker = new GMarker(point);
              map.addOverlay(marker);
              marker.openInfoWindowHtml(address);
            }
          }
        );
      }
    }
	showAddress('<?php echo $_GET['addr'];?>');
    </script>
</html>