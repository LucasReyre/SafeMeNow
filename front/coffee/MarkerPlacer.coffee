class MarkerPlacer
  @mapInfowindowSelector = 'mapInfowindow'

  constructor: (@mapSelector) ->
    $(@mapSelector).gmap3()
    window.infoWindow = new google.maps.InfoWindow
      content : "<div id='#{MarkerPlacer.mapInfowindowSelector}'></div>"

  addMarkers: (data) ->
    markers = []
    markers = markers.concat(@getShelters(data.shelters))
    markers = markers.concat(@getInjuredPeople(data.injured))
    markers = markers.concat(@getUnsafe(data.unsafe))
    debugger
    mapParams =
      clear:
          name: [
              "shelter"
              "injured"
              "unsafe"
              "infowindow"
          ]
      marker:
          values: markers
    $(@mapSelector).gmap3(mapParams) # apply modifications
    $(@mapSelector).gmap3("autofit") # center on icons

  getInjuredPeople: (data) ->
      # set injured people in marker formats
      debugger
      if data
          injured = data.map (x) ->
              latLng: [x.lat, x.lng]
              options:
                  icon: "./imgs/injured.png"
              tag: "injured"
              events:
                  click: (marker, events, context) ->
                      console.log "Click injured"
      else
          injured = []
      return injured

    getUnsafe: (data) ->
        # set people who needs help in marker formats
        debugger
        if data
            unsafe = data.map (x) ->
                latLng: [x.lat, x.lng]
                options:
                    icon: "./imgs/unsafe.png"
                tag: "unsafe"
                events:
                    click: (marker, events, context) ->
                        console.log "Click unsafe"
        else
            unsafe = []
        return unsafe

  getShelters: (data) ->
      # set shelters in marker formats
      if data
          shelters = data.map (x) ->
              latLng: [x.lat, x.lng]
              options:
                  icon: "./imgs/shelter.png"
              tag: "shelter"
              events:
                  click: (marker, events, context) ->
                      console.log "Click shelter"
      else
          shelters = []
      return shelters
