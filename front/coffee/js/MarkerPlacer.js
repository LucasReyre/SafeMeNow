var MarkerPlacer;

MarkerPlacer = (function() {
  MarkerPlacer.mapInfowindowSelector = 'mapInfowindow';

  function MarkerPlacer(mapSelector) {
    this.mapSelector = mapSelector;
    $(this.mapSelector).gmap3();
    window.infoWindow = new google.maps.InfoWindow({
      content: "<div id='" + MarkerPlacer.mapInfowindowSelector + "'></div>"
    });
    $("body").on("click", ".contact-shelter", function() {
      return console.log("TODO: ouvrir chat");
    });
  }

  MarkerPlacer.prototype.addMarkers = function(data) {
    var mapParams, markers;
    markers = [];
    markers = markers.concat(this.getShelters(data.shelters));
    markers = markers.concat(this.getInjuredPeople(data.injured));
    markers = markers.concat(this.getUnsafe(data.unsafe));
    mapParams = {
      clear: {
        name: ["shelter", "injured", "unsafe", "infowindow"]
      },
      marker: {
        values: markers
      }
    };
    $(this.mapSelector).gmap3(mapParams);
    return $(this.mapSelector).gmap3("autofit");
  };

  MarkerPlacer.prototype.getInjuredPeople = function(data) {
    var injured;
    if (data) {
      injured = data.map(function(x) {
        return {
          latLng: [x.lat, x.lng],
          options: {
            icon: "./imgs/injured.png"
          },
          tag: "injured",
          events: {
            click: function(marker, events, context) {
              return console.log("Click injured");
            }
          }
        };
      });
    } else {
      injured = [];
    }
    return injured;
  };

  MarkerPlacer.prototype.getUnsafe = function(data) {
    var unsafe;
    if (data) {
      unsafe = data.map(function(x) {
        return {
          latLng: [x.lat, x.lng],
          options: {
            icon: "./imgs/unsafe.png"
          },
          tag: "unsafe",
          events: {
            click: function(marker, events, context) {
              return console.log("Click unsafe");
            }
          }
        };
      });
    } else {
      unsafe = [];
    }
    return unsafe;
  };

  MarkerPlacer.prototype.getShelters = function(data) {
    var shelters;
    if (data) {
      shelters = data.map(function(x) {
        return {
          latLng: [x.lat, x.lng],
          options: {
            icon: "./imgs/shelter.png"
          },
          data: {
            options: {
              content: "<div class='infowindow shelter'> <span class='informations'>" + x.addr + "</span> <span class='contact-shelter'>Contacter</span> </div>"
            }
          },
          tag: "shelter",
          events: {
            click: function(marker, events, context) {
              var infowindow, map;
              map = $(this).gmap3("get");
              infowindow = $(this).gmap3({
                get: {
                  name: "infowindow"
                }
              });
              if (infowindow) {
                infowindow.open(map, marker);
                return infowindow.setContent(context.data.options.content);
              } else {
                return $(this).gmap3({
                  infowindow: {
                    anchor: marker,
                    options: {
                      content: context.data.options.content
                    }
                  }
                });
              }
            }
          }
        };
      });
    } else {
      shelters = [];
    }
    return shelters;
  };

  return MarkerPlacer;

})();
