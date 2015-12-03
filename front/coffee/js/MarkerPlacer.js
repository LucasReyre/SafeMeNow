var MarkerPlacer;

MarkerPlacer = (function() {
  MarkerPlacer.mapInfowindowSelector = 'mapInfowindow';

  function MarkerPlacer(mapSelector) {
    this.mapSelector = mapSelector;
    $(this.mapSelector).gmap3();
    window.infoWindow = new google.maps.InfoWindow({
      content: "<div id='" + MarkerPlacer.mapInfowindowSelector + "'></div>"
    });
  }

  MarkerPlacer.prototype.addMarkers = function(data) {
    var mapParams, markers;
    markers = [];
    markers = markers.concat(this.getShelters(data.shelters));
    markers = markers.concat(this.getInjuredPeople(data.injured));
    markers = markers.concat(this.getUnsafe(data.unsafe));
    debugger;
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
    debugger;
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
    debugger;
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
          tag: "shelter",
          events: {
            click: function(marker, events, context) {
              return console.log("Click shelter");
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
