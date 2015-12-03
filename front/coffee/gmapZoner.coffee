class GmapZoner
  @mapInfowindowSelector = 'mapInfowindow'

  initMap: ->
    window.infoWindow = new google.maps.InfoWindow
      content : "<div id='#{GmapZoner.mapInfowindowSelector}'></div>"

  constructor: (@mapSelector) ->
    $(@mapSelector).gmap3()
    $(@mapSelector).gmap3("get").addListener 'zoom_changed', () ->
    	if $("#{@mapInfowindowSelector}").length isnt 0
    		$("#{@mapInfowindowSelector}").highcharts().redraw();

  setMultipleZone: (zoneList) ->
    for zone in zoneList
      @setZone(zone)
    $(@mapSelector).gmap3("autofit")

  drawCity: (city) ->
    $(@mapSelector).gmap3
      polygon:
        options:
          storeColor: "#ff0000"
          strokeOpacity: 0.8
          strokeWeight: 2
          fillColor: "#ff0000"
          fillOpacity: 0
          clickable: false
          paths: city.getCoords()

  setZone: (z) ->
    $(@mapSelector).gmap3
      polygon:
        options:
          storeColor: z.getColor()
          strokeOpacity: 0.1
          strokeWeight: 2
          fillColor: z.getColor()
          fillOpacity: 0.35
          clickable: true
          paths: z.getPath()
        events:
          click : (poly, args, context) ->
            lat = poly.getBounds().getNorthEast().lat()
            lng = poly.getBounds().getCenter().lng()
            #args.latLng.lng()
            window.infoWindow.setPosition(
              lat: lat,
              lng: lng
            )
            window.infoWindow.open $(@).gmap3("get")
            Zone.draw()
