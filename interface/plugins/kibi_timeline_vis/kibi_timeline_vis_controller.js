define(function (require) {
  require('./kibi_timeline');
  require('ui/modules');

  var _ = require('lodash');

  var module = require('ui/modules').get('kibi_timeline_vis/kibi_timeline_vis', ['kibana']);
  module.controller(
    'KbnTimelineVisController',
    function (createNotifier, $location, $rootScope, $scope, $route, savedSearches, savedVisualizations, Private, $element, Promise) {

      var notify = createNotifier({
        location: 'Kibi Timeline'
      });

      var SearchSource = Private(require('components/courier/data_source/search_source'));
      var requestQueue = Private(require('./lib/courier/_request_queue_wrapped'));

      function initOptions(savedVis) {
        var height = $element[0].offsetHeight;
        // make sure that it is never too small
        // as the height might be reported wrongly when element is not yet fully rendered
        if (height >= 20) {
          height -= 20;
        }
        if (height < 175) {
          height = 175;
        }
        var options = {
          width: '100%',
          height: height + 'px',
          selectable: true,
          // ! does not work correctly inside the panel
          // instead we would have to calculate the proper height on panel resize and change it
          // on change timeline directive should call redraw()
          autoResize: false
        };
        $scope.options = options;
      }


      function initSearchSources(savedVis) {
        // here iterate over groups from savedVis.vis.params.groups
        var promises = [];
        _.each(savedVis.vis.params.groups, function (group) {
          if (group.savedSearchId) {
            promises.push(
              savedSearches.get(group.savedSearchId).then(function (savedSearch) {
                return {
                  savedSearch: savedSearch,
                  group: group
                };
              })
            );
          }
        });

        Promise.all(promises).then(function (results) {
          var groups = [];

          _.each(results, function (result) {
            var savedSearch = result.savedSearch;
            var group = result.group;

            var _id = '_kibi_timetable_ids_source_flag' + savedSearch.id; // used only by kibi
            requestQueue.markAllRequestsWithSourceIdAsInactive(_id);      //

            var searchSource = new SearchSource();
            searchSource.inherits(savedSearch.searchSource);
            searchSource._id = _id;
            searchSource.index(savedSearch.searchSource._state.index);
            searchSource.size(group.size || 100);

            groups.push({
              id: group.id,
              color: group.color,
              label: group.groupLabel,
              searchSource: searchSource,
              params: {
                labelField: group.labelField,
                startField: group.startField,
                endField: group.endField,
                size: group.size
              }
            });
          });

          $scope.savedObj.groups = groups;
          $scope.savedObj.groupsOnSeparateLevels = savedVis.vis.params.groupsOnSeparateLevels;
        });
      }


      $scope.savedObj = {
        groups: []
      };
      // Set to true in editing mode
      var configMode = $location.path().indexOf('/visualize/') !== -1;

      $scope.savedVis = $route.current.locals.savedVis;
      if (!$scope.savedVis) {
        // NOTE: reloading the visualization to get the searchSource,
        // which would otherwise be unavailable by design
        if ($scope.vis.id) {
          savedVisualizations.get($scope.vis.id).then(function (savedVis) {
            $scope.vis = savedVis.vis;
            $scope.savedVis = savedVis;
          }).catch(notify.error);
        } else {
          savedVisualizations.find($scope.vis.title).then(function (results) {
            const vis = _.find(results.hits, function (hit) {
              return hit.title === $scope.vis.title;
            });
            if (!vis) {
              notify.error('Unable to find visualization with title == "' + $scope.vis.title + '"');
              return;
            }
            return savedVisualizations.get(vis.id).then(function (savedVis) {
              $scope.vis = savedVis.vis;
              $scope.vis.id = vis.id;
              $scope.savedVis = savedVis;
            });
          }).catch(notify.error);
        }
      }

      $scope.$on('change:vis', function () {
        initOptions($scope.savedVis);
      });

      $scope.$watch('savedVis', function () {
        if ($scope.savedVis) {
          initOptions($scope.savedVis);
          initSearchSources($scope.savedVis);
        }
      });

      // used also in autorefresh mode
      $scope.$watch('esResponse', function () {
        if ($scope.savedObj && $scope.savedObj.searchSources) {
          _.each($scope.savedObj.searchSources, function (ss) {
            ss.fetchQueued();
          });
        }
      });

      if (configMode) {
        var removeVisStateChangedHandler = $rootScope.$on('kibi:vis:state-changed', function () {
          initOptions($scope.savedVis);
          initSearchSources($scope.savedVis);
        });

        $scope.$on('$destroy', function () {
          removeVisStateChangedHandler();
        });
      }

    });
});

