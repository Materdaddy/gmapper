# Objectives #
The objective of this is to create google maps (like this project: http://blog.chiggins.com/?p=35) but have it more scalable.  I want to use design patterns to design and architect this mapping library for use in many scalable options.  In other words, not just google maps with push pins for wordpress like the wordpress plugin linked.  I want to be able to add arbitrary objects (geo tagged photos, gps waypoints, weather balloon points, etc., tracks/lines, directions) onto maps that are completely customizable using a simple interface.

# Admin Interface #
The idea is to have an HTML/JavaScript interface that will be used to create and edit any number of maps.

## Map Representation ##
The map will be a data structure represented by XML.  This data can be stored as straight XML on the filesystem, or stored into a database, or any other method.  The DTD for this XML layout is TBD at this point.

## Object Representation ##
An object will be a generic container for anything displayable on a map using Google Maps API v2.  This can be a photo, a point with URL/Description/etc, or even waypoints and tracks imported from a GPX file if a 'gpx widget' is created.

# May Display #
The map display will be something that can accept the XML representation of the map and display it by creating the necessary JavaScript using Google Maps API v2.