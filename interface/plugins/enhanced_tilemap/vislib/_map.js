'use strict';

var _markerIcon = require('plugins/enhanced_tilemap/vislib/markerIcon');

define(function (require) {
  return function MapFactory(Private) {
    var _ = require('lodash');
    var $ = require('jquery');
    var L = require('leaflet');
		require('style!css!node_modules/leaflet/dist/leaflet.css');
		require('style!css!node_modules/leaflet-draw/dist/leaflet.draw.css');
    var LDrawToolbench = require('./LDrawToolbench');
    var utils = require('plugins/enhanced_tilemap/utils');
    var formatcoords = require('./../lib/formatcoords/index');
    require('./../lib/leaflet.mouseposition/L.Control.MousePosition.css');
    require('./../lib/leaflet.mouseposition/L.Control.MousePosition');
    require('./../lib/leaflet.setview/L.Control.SetView.css');
    require('./../lib/leaflet.setview/L.Control.SetView');
    require('./../lib/leaflet.measurescale/L.Control.MeasureScale.css');
    require('./../lib/leaflet.measurescale/L.Control.MeasureScale');
    var syncMaps = require('./sync_maps');

    var defaultMapZoom = 2;
    var defaultMapCenter = [15, 5];
    var defaultMarkerType = 'Scaled Circle Markers';

    var mapTiles = {
      url: 'http://{s}.tile.osm.org/{z}/{x}/{y}.png',
      options: {
        attribution: 'Map data © <a href="http://openstreetmap.org">OpenStreetMap</a> contributors',
				subdomains: ['a','b','c']
      }
    };
    var markerTypes = {
      'Scaled Circle Markers': Private(require('./marker_types/scaled_circles')),
      'Shaded Circle Markers': Private(require('./marker_types/shaded_circles')),
      'Shaded Geohash Grid': Private(require('./marker_types/geohash_grid')),
      'Heatmap': Private(require('./marker_types/heatmap'))
    };

    /**
     * Tile Map Maps
     *
     * @class Map
     * @constructor
     * @param container {HTML Element} Element to render map into
     * @param params {Object} Parameters used to build a map
     */
    function TileMapMap(container, params) {
      this._container = container;
      this._poiLayers = {};
      this._wmsOverlays = [];

      // keep a reference to all of the optional params
      this._callbacks = _.get(params, 'callbacks');
      this._setMarkerType(params.mapType);
      var centerArray = _.get(params, 'center') || defaultMapCenter;
      this._mapCenter = L.latLng(centerArray[0], centerArray[1]);
      this._mapZoom = _.get(params, 'zoom') || defaultMapZoom;
      this._setAttr(params.attr);
      this._isEditable = params.editable || false;

      var mapOptions = {
        minZoom: 1,
        maxZoom: 18,
        noWrap: true,
        maxBounds: L.latLngBounds([-90, -220], [90, 220]),
        scrollWheelZoom: _.get(params.attr, 'scrollWheelZoom', true),
        fadeAnimation: false
      };
      this._createMap(mapOptions);
    }

    TileMapMap.prototype._addDrawControl = function () {
      if (this._drawControl) return;

      //create Markers feature group and add saved markers 
      this._drawnItems = new L.FeatureGroup();
      var self = this;
      this._attr.markers.forEach(function (point) {
        var color = '#d473ff';
        if (point.length === 3) {
          color = point.pop();
        }
        self._drawnItems.addLayer(L.marker(point, { icon: (0, _markerIcon.markerIcon)(color) }));
      });
      this.map.addLayer(this._drawnItems);
      this._layerControl.addOverlay(this._drawnItems, "Markers");

      //https://github.com/Leaflet/Leaflet.draw
      var drawOptions = {
        draw: {
          circle: false,
          marker: {
            icon: (0, _markerIcon.markerIcon)('#d473ff')
          },
          polygon: false,
          polyline: false,
          rectangle: {
            shapeOptions: {
              stroke: false,
              color: '#000'
            }
          }
        },
        edit: {
          featureGroup: this._drawnItems,
          edit: false
        }
      };
      //Do not show marker and remove buttons when visualization is displayed in dashboard, i.e. not editable 
      if (!this._isEditable) {
        drawOptions.draw.marker = false;
        drawOptions.edit.remove = false;
      }

      this._drawControl = new L.Control.Draw(drawOptions);
      this.map.addControl(this._drawControl);

      this._toolbench = new LDrawToolbench(this.map, this._drawControl);
    };

    TileMapMap.prototype._addSetViewControl = function () {
      if (this._setViewControl) return;

      this._setViewControl = new L.Control.SetView();
      this.map.addControl(this._setViewControl);
    };

    TileMapMap.prototype._addMousePositionControl = function () {
      if (this._mousePositionControl) return;

      var dd = function dd(val) {
        return L.Util.formatNum(val, 5);
      };
      var space = "replaceMe";
      this._mousePositionControl = L.control.mousePosition({
        emptyString: '',
        lngFormatters: [dd, function (lon) {
          var dms = formatcoords(0, lon).format('DD MM ss X', {
            latLonSeparator: space,
            decimalPlaces: 2
          });
          return dms.substring(dms.indexOf(space) + space.length);
        }],
        latFormatters: [dd, function (lat) {
          var dms = formatcoords(lat, 0).format('DD MM ss X', {
            latLonSeparator: space,
            decimalPlaces: 2
          });
          return dms.substring(0, dms.indexOf(space));
        }]
      });
      this.map.addControl(this._mousePositionControl);
    };

    /**
     * Adds label div to each map when data is split
     *
     * @method addTitle
     * @param mapLabel {String}
     * @return {undefined}
     */
    TileMapMap.prototype.addTitle = function (mapLabel) {
      if (this._label) return;

      var label = this._label = L.control();

      label.onAdd = function () {
        this._div = L.DomUtil.create('div', 'tilemap-info tilemap-label');
        this.update();
        return this._div;
      };
      label.update = function () {
        this._div.innerHTML = '<h2>' + _.escape(mapLabel) + '</h2>';
      };

      // label.addTo(this.map);
      this.map.addControl(label);
    };

    /**
     * remove css class for desat filters on map tiles
     *
     * @method saturateTiles
     * @return undefined
     */
    TileMapMap.prototype.saturateTiles = function (isDesaturated) {
      if (isDesaturated) {
        $(this._tileLayer.getContainer()).removeClass('no-filter');
      } else {
        $(this._tileLayer.getContainer()).addClass('no-filter');
      }
    };

    TileMapMap.prototype.updateSize = function () {
      this.map.invalidateSize({
        debounceMoveend: true
      });
    };

    /*TileMapMap.prototype.fitToData = function () {
      var markers = []
      var that = this;

      Object.keys(this.map._layers).forEach(function (ml) {
        let layer = that.map._layers[ml]
        if (layer._latlngs) {
          console.log(layer._latlngs)
          if (layer._latlngs[0].lat && layer._latlngs[0].lng) {
            var a = layer._latlngs.reduce((acc, latlng) => {
              acc = acc.concat([[latlng.lat, latlng.lng]])
              return acc
            }, [])

            markers = a
          } else {
            markers = markers.concat(layer._latlngs)
          }
        } else if (layer._latlng) {
          markers = markers.concat([[layer._latlng.lat, layer._latlng.lng]])
        }
      })
      // Object.keys(this.map._layers).forEach(function (ml) {
      //   if (that.map._layers[ml]._latlngs) {
      //     markers = markers.concat(that.map._layers[ml]._latlngs)
      //   }
      // })
      if (markers.length <= 0) return;

      var getBound = function (axis, min) {
        return markers.reduce(function(acc, point) {
          if (min) return point[axis] < acc ? point[axis] : acc;
          return point[axis] > acc ? point[axis] : acc;
        }, markers[0][axis]);
      }

      var xSize = Math.abs(getBound(0, true) - getBound(0, false));
      var ySize = Math.abs(getBound(1, true) - getBound(1, false));
      var x = xSize / 2 + getBound(0, true);
      var y = ySize / 2 + getBound(1, true);
      var size = xSize > ySize ? xSize : ySize;

      this.map.setView([x, y], Math.round(size))
    };*/

    TileMapMap.prototype.destroy = function () {
      if (this._label) this._label.removeFrom(this.map);
      if (this._fitControl) this._fitControl.removeFrom(this.map);
      if (this._drawControl) this._drawControl.removeFrom(this.map);
      if (this._markers) this._markers.destroy();
     // syncMaps.remove(this.map);
      this.map.remove();
      this.map = undefined;
    };

    TileMapMap.prototype.clearPOILayers = function () {
      var self = this;
      Object.keys(this._poiLayers).forEach(function (key) {
        var layer = self._poiLayers[key];
        self._layerControl.removeLayer(layer);
        self.map.removeLayer(layer);
      });
      this._poiLayers = {};
      if (this._toolbench) this._toolbench.removeTools();
    };

    TileMapMap.prototype.addPOILayer = function (layerName, layer) {
      //remove layer if it already exists
      if (_.has(this._poiLayers, layerName)) {
        var _layer = this._poiLayers[layerName];
        this._layerControl.removeLayer(_layer);
        this.map.removeLayer(_layer);
        delete this._poiLayers[layerName];
      }

      this.map.addLayer(layer);
      this._layerControl.addOverlay(layer, layerName);
      this._poiLayers[layerName] = layer;

      //Add tool to l.draw.toolbar so users can filter by POIs
      if (Object.keys(this._poiLayers).length === 1) {
        if (this._toolbench) this._toolbench.removeTools();;
        if (!this._toolbench) this._addDrawControl();
        this._toolbench.addTool();
      }
    };

    /**
     * Switch type of data overlay for map:
     * creates featurelayer from mapData (geoJson)
     *
     * @method _addMarkers
     */
    TileMapMap.prototype.addMarkers = function (chartData, newParams, tooltipFormatter, valueFormatter, collar) {
      this._setMarkerType(newParams.mapType);
      this._setAttr(newParams);
      this._chartData = chartData;
      this._geoJson = _.get(chartData, 'geoJson');
      this._collar = collar;

      var visible = true;
      if (this._markers) {
        visible = this._markers.isVisible();
        this._markers.destroy();
      }

      this._markers = this._createMarkers({
        tooltipFormatter: tooltipFormatter,
        valueFormatter: valueFormatter,
        visible: visible,
        attr: this._attr
      });

      if (this._geoJson.features.length > 1) {
        this._markers.addLegend();
      }
    };

    /**
     * Display geospatial filters as map layer to provide 
     * users context for all applied filters
     */
    TileMapMap.prototype.addFilters = function (filters) {
      var isVisible = false;
      if (this._filters) {
        if (this.map.hasLayer(this._filters)) {
          isVisible = true;
        }
        this._layerControl.removeLayer(this._filters);
        this.map.removeLayer(this._filters);
      }

      var style = {
        fillColor: "#ccc",
        color: "#ccc",
        weight: 1.5,
        opacity: 1,
        fillOpacity: 0.75
      };
      this._filters = L.featureGroup(filters);
      this._filters.setStyle(style);
      if (isVisible) this.map.addLayer(this._filters);
      this._layerControl.addOverlay(this._filters, "Applied Filters");
    };

    TileMapMap.prototype.clearWMSOverlays = function () {
      var self = this;
      this._wmsOverlays.forEach(function (layer) {
        self._layerControl.removeLayer(layer);
        self.map.removeLayer(layer);
      });
      this._wmsOverlays = [];
    };

    TileMapMap.prototype.addWmsOverlay = function (url, name, options) {
      var overlay = L.tileLayer.wms(url, options);
      this.map.addLayer(overlay);
      this._layerControl.addOverlay(overlay, name);
      this._wmsOverlays.push(overlay);
      $(overlay.getContainer()).addClass('no-filter');
    };

    TileMapMap.prototype.mapBounds = function () {
      var bounds = this.map.getBounds();

      //When map is not visible, there is no width or height. 
      //Need to manually create bounds based on container width/height
      if (bounds.getNorthWest().equals(bounds.getSouthEast())) {
        var parent = this._container;
				if(parent) {
					while (parent.clientWidth === 0 && parent.clientHeight === 0) {
       	    parent = parent.parentNode ? parent.parentNode : parent;
       	  }

       	  var southWest = this.map.layerPointToLatLng(L.point(parent.clientWidth / 2 * -1, parent.clientHeight / 2 * -1));
       	  var northEast = this.map.layerPointToLatLng(L.point(parent.clientWidth / 2, parent.clientHeight / 2));
       	  bounds = L.latLngBounds(southWest, northEast);
				}
      }
      return bounds;
    };

    TileMapMap.prototype.mapZoom = function () {
      return this.map.getZoom();
    };

    /**
     * Create the marker instance using the given options
     *
     * @method _createMarkers
     * @param options {Object} options to give to marker class
     * @return {Object} marker layer
     */
    TileMapMap.prototype._createMarkers = function (options) {
      var MarkerType = markerTypes[this._markerType];
      return new MarkerType(this.map, this._geoJson, this._layerControl, options);
    };

    TileMapMap.prototype._setMarkerType = function (markerType) {
      this._markerType = markerTypes[markerType] ? markerType : defaultMarkerType;
    };

    TileMapMap.prototype._setAttr = function (attr) {
      this._attr = attr || {};

      //Ensure plugin is backwards compatible with old saved state values
      if ('static' === this._attr.scaleType) {
        this._attr.scaleType = 'Static';
      } else if ('dynamic' === this._attr.scaleType) {
        this._attr.scaleType = 'Dynamic - Linear';
      }

      //update map options based on new attributes
      if (this.map) {
        if (this._attr.scrollWheelZoom) {
          this.map.scrollWheelZoom.enable();
        } else {
          this.map.scrollWheelZoom.disable();
        }
      }
    };

    TileMapMap.prototype._attachEvents = function () {
      var self = this;

      this.map.on('moveend', _.debounce(function setZoomCenter(ev) {
        if (!self.map) return;
        if (self._hasSameLocation()) return;

        // update internal center and zoom references
        self._mapCenter = self.map.getCenter();
        self._mapZoom = self.map.getZoom();

        self._callbacks.mapMoveEnd({
          chart: self._chartData,
          collar: self._collar,
          mapBounds: self.mapBounds(),
          map: self.map,
          center: self._mapCenter,
          zoom: self._mapZoom
        });
      }, 150, false));

      this.map.on('setview:fitBounds', function (e) {
        self._fitBounds();
      });

      this.map.on('toolbench:poiFilter', function (e) {
        var poiLayers = [];
        Object.keys(self._poiLayers).forEach(function (key) {
          poiLayers.push(self._poiLayers[key]);
        });
        self._callbacks.poiFilter({
          chart: self._chartData,
          poiLayers: poiLayers,
          radius: _.get(e, 'radius', 10)
        });
      });

      this.map.on('draw:created', function (e) {
        (function () {
          switch (e.layerType) {
            case "marker":
              self._drawnItems.addLayer(e.layer);
              self._callbacks.createMarker({
                e: e,
                chart: self._chartData,
                latlng: e.layer._latlng
              });
              break;
            case "polygon":
              var points = [];
              e.layer._latlngs.forEach(function (latlng) {
                var lat = L.Util.formatNum(latlng.lat, 5);
                var lon = L.Util.formatNum(latlng.lng, 5);
                points.push([lon, lat]);
              });
              self._callbacks.polygon({
                chart: self._chartData,
                params: self._attr,
                points: points
              });
              break;
            case "rectangle":
              self._callbacks.rectangle({
                e: e,
                chart: self._chartData,
                params: self._attr,
                bounds: utils.scaleBounds(e.layer.getBounds(), 1)
              });
              break;
            default:
              console.log("draw:created, unexpected layerType: " + e.layerType);
          }
        })();
      });

      this.map.on('draw:deleted', function (e) {
        self._callbacks.deleteMarkers({
          chart: self._chartData,
          deletedLayers: e.layers
        });
      });

      this.map.on('zoomend', _.debounce(function () {
        if (!self.map) return;
        if (self._hasSameLocation()) return;
        if (!self._callbacks) return;
        self._callbacks.mapZoomEnd({
          chart: self._chartData,
          map: self.map,
          zoom: self.map.getZoom()
        });
      }, 150, false));

      this.map.on('overlayadd', function (e) {
        if (self._markers && e.name === "Aggregation") {
          self._markers.show();
        }
      });

      this.map.on('overlayremove', function (e) {
        if (self._markers && e.name === "Aggregation") {
          self._markers.hide();
        }
      });
    };

    TileMapMap.prototype._hasSameLocation = function () {
      var oldLat = this._mapCenter.lat.toFixed(5);
      var oldLon = this._mapCenter.lng.toFixed(5);
      var newLat = this.map.getCenter().lat.toFixed(5);
      var newLon = this.map.getCenter().lng.toFixed(5);
      var isSame = false;
      if (oldLat === newLat && oldLon === newLon && this.map.getZoom() === this._mapZoom) {
        isSame = true;
      }
      return isSame;
    };

    TileMapMap.prototype._createMap = function (mapOptions) {
      if (this.map) this.destroy();

      // add map tiles layer, using the mapTiles object settings
      if (this._attr.wms && this._attr.wms.enabled) {
        this._tileLayer = L.tileLayer.wms(this._attr.wms.url, this._attr.wms.options);
      } else {
        this._tileLayer = L.tileLayer(mapTiles.url, mapTiles.options);
      }

      // append tile layers, center and zoom to the map options
      mapOptions.layers = this._tileLayer;
      mapOptions.center = this._mapCenter;
      mapOptions.zoom = this._mapZoom;

      this.map = L.map(this._container, mapOptions);
      this._layerControl = L.control.layers();
      this._layerControl.addTo(this.map);

      this._addSetViewControl();
      this._addDrawControl();
      this._addMousePositionControl();
      L.control.measureScale().addTo(this.map);
      this._attachEvents();
//      syncMaps.add(this.map);
    };

    /**
     * zoom map to fit all features in featureLayer
     *
     * @method _fitBounds
     * @param map {Leaflet Object}
     * @return {boolean}
     */
    TileMapMap.prototype._fitBounds = function () {
      var bounds = this._getDataRectangles();
      if (bounds.length > 0) {
        this.map.fitBounds(bounds);
      }
    };

    /**
     * Get the Rectangles representing the geohash grid
     *
     * @return {LatLngRectangles[]}
     */
    TileMapMap.prototype._getDataRectangles = function () {
      if (!this._geoJson) return [];
      var a = _.map(this._geoJson.features, 'properties');
			var b = _.map(a, 'rectangle');
      return b;
    };

    return TileMapMap;
  };
});
