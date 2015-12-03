class City
  constructor: (data) ->
    @_id = data.id_ville
    coords = document.fulldata.coordonnee_ville.filter (x) ->
        x.id_ville == data.id_ville
    @_zones = data.zones
    @_coordinates = []
    for coord in coords
      @_coordinates.push [coord.latitude, coord.longitude]

  getCoords: () ->
    return @_coordinates
