class Zone
  constructor: (data) ->
    @_id = data.id_zone
    coords = document.fulldata.coordonnee_zone.filter (x) ->
        x.id_zone == data.id_zone
    @_indicateurs = document.fulldata.indicateur.filter (x) ->
        x.id_zone == data.id_zone
    indices =  document.fulldata.indices_zone.filter (x) ->
        x.id_zone == data.id_zone
    @_indice = indices[0]
    @_coordinates = []
    for coord in coords
      @_coordinates.push [coord.latitude, coord.longitude]

  @draw: () ->
    $("##{GmapZoner.mapInfowindowSelector}").highcharts
      chart:
        type: 'column'
      title:
        text: 'Stacked column chart'
      xAxis:
        categories: ['Apples', 'Oranges', 'Pears', 'Grapes', 'Bananas']
      yAxis:
        min: 0
        title:
          text: 'Total fruit consumption'
      tooltip:
        pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.y}</b> ({point.percentage:.0f}%)<br/>',
        shared: true
      plotOptions:
        column:
          stacking: 'percent'
      series: [
        { name: 'John', data: [5, 3, 4, 7, 2] },
        { name: 'Jane', data: [2, 2, 3, 2, 1] },
        { name: 'Joe', data: [3, 4, 4, 2, 5] }
      ]

  getColor: () ->
    return ZoneColorEnum["#{@_indice.indice}"]

  getPath: () ->
    return @_coordinates
