class MarkerPlacer
  @mapInfowindowSelector = 'mapInfowindow'

  constructor: (@mapSelector) ->
    $(@mapSelector).gmap3()
    window.infoWindow = new google.maps.InfoWindow
      content : "<div id='#{MarkerPlacer.mapInfowindowSelector}'></div>"

    $("body").on "click", ".contact-shelter", () ->
      console.log "TODO: ouvrir chat"

  addMarkers: (data) ->
    markers = []
    markers = markers.concat(@getShelters(data.shelters))
    markers = markers.concat(@getInjuredPeople(data.injured))
    markers = markers.concat(@getUnsafe(data.unsafe))

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
      if data
          injured = data.map (x) ->
              latLng: [x.lat, x.lng]
              options:
                  icon: "./img/injured.png"
                  clickable: false
              tag: "injured"
              events:
                  click: (marker, events, context) ->
                      console.log "Click injured"
      else
          injured = []
      return injured

    getUnsafe: (data) ->
        # set people who needs help in marker formats
        if data
            unsafe = data.map (x) ->
                latLng: [x.lat, x.lng]
                options:
                    icon: "./img/unsafe.png"
                    clickable: false
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
                  icon: "./img/shelter.png"
              data:
                options:
                  content: "
                    <div class='infowindow shelter'>
                      <span class='informations'>#{x.addr}</span>
                      <span class='contact-shelter'>Contacter</span>
                    </div>"
              tag: "shelter"
              events:
                  click: (marker, events, context) ->
                    map = $(this).gmap3("get")
                    infowindow = $(this).gmap3({get:{name:"infowindow"}})

                    if infowindow
                      infowindow.open map, marker
                      infowindow.setContent context.data.options.content
                    else
                      $(this).gmap3
                        infowindow:
                          anchor:marker
                          options:
                            content: context.data.options.content

      else
          shelters = []
      return shelters
