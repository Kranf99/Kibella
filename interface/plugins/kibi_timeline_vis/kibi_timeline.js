define(function (require) {

  var _ = require('lodash');
  var vis = require('vis');
  var moment = require('moment');

  require('ui/modules').get('kibana').directive('kibiTimeline', function (Private, createNotifier, courier) {

    var filterManager = Private(require('components/filter_manager/filter_manager'));
    var requestQueue = Private(require('./lib/courier/_request_queue_wrapped'));
    var timelineHelper = Private(require('./lib/helpers/timeline_helper'));

    var notify = createNotifier({
      location: 'Kibi Timeline'
    });

    return {
      scope: {
        groups: '=',
        groupsOnSeparateLevels: '=',
        options: '=',
      },
      restrict: 'E',
      replace: true,
      link: _link
    };

    function _link($scope, $element) {
      var timeline;
      var data;

      var onSelect = function (properties) {
        // pass this to a scope variable
        var selected = data._data[properties.items];
        if (selected) {
          var index = selected.index;
          var field = selected.field;
          var value = selected.value;
          var operator = '+';
          filterManager.add(field, value, operator, index);
        }
      };

      var initTimeline = function () {
        if (!timeline) {
          // create a new one
          timeline = new vis.Timeline($element[0]);
          if ($scope.options) {
            timeline.setOptions($scope.options);
          }
          timeline.on('select', onSelect);
        }
      };

      var groupEvents = [];

      var updateTimeline = function (groupIndex, events) {
        initTimeline();
        var existingGroupIds = _.map($scope.groups, function (g) {
          return g.id;
        });

        groupEvents[groupIndex] = _.cloneDeep(events);

        // make sure all events have correct group index
        // add only events from groups which still exists
        var points = [];
        _.each(groupEvents, function (events, index) {
          _.each(events, function (e) {
            e.group = $scope.groupsOnSeparateLevels === true ? index : 0;
            if (existingGroupIds.indexOf(e.groupId) !== -1) {
              points.push(e);
            }
          });
        });

        data = new vis.DataSet(points);
        timeline.setItems(data);
        timeline.fit();
      };

      var initSingleGroup = function (group, index) {
        var searchSource = group.searchSource;
        var params = group.params;
        var groupId = group.id;
        const groupColor = group.color;
        searchSource.onResults().then(function onResults(searchResp) {
          var events = [];

          if (params.startField) {
            var detectedMultivaluedLabel;
            var detectedMultivaluedStart;
            var detectedMultivaluedEnd;
            var labelFieldValue;
            var startFieldValue;
            var endFieldValue;

            _.each(searchResp.hits.hits, function (hit) {
              var labelFieldValue = timelineHelper.getDescendantPropValue(hit._source, params.labelField);
              var startFieldValue = timelineHelper.getDescendantPropValue(hit._source, params.startField);
              var endFieldValue = null;

              if (startFieldValue) {

                if (timelineHelper.isMultivalued(startFieldValue)) {
                  detectedMultivaluedStart = true;
                }
                if (timelineHelper.isMultivalued(labelFieldValue)) {
                  detectedMultivaluedLabel = true;
                }
                var indexId = searchSource.get('index').id;
                var startValue = timelineHelper.pickFirstIfMultivalued(startFieldValue);
                var labelValue = timelineHelper.pickFirstIfMultivalued(labelFieldValue, '');
                var e =  {
                  // index, field and content needed to create a filter on click
                  index: indexId,
                  field: params.labelField,
                  content: '<div title="index: ' + indexId + ', field: ' + params.labelField + '">' + labelValue + '</div>',
                  value: labelValue,
                  start: moment(startValue).toDate(),
                  type: 'box',
                  group: $scope.groupsOnSeparateLevels === true ? index : 0,
                  style: 'background-color: ' + groupColor + '; color: #fff;',
                  groupId: groupId
                };

                if (params.endField) {
                  endFieldValue = timelineHelper.getDescendantPropValue(hit._source, params.endField);
                  if (timelineHelper.isMultivalued(endFieldValue)) {
                    detectedMultivaluedEnd = true;
                  }
                  if (!endFieldValue) {
                    // here the end field value missing but expected
                    // force the event to be of type point
                    e.type = 'point';
                  } else {
                    var endValue = timelineHelper.pickFirstIfMultivalued(endFieldValue);
                    if (startValue === endValue) {
                      // also force it to be a point
                      e.type = 'point';
                    } else {
                      e.type = 'range';
                      e.end = moment(endValue).toDate();
                    }
                  }
                }
                events.push(e);
              }
            });

            if (detectedMultivaluedLabel) {
              notify.warning('Label field [' + params.labelField + '] is multivalued - the first value will be used.');
            }
            if (detectedMultivaluedStart) {
              notify.warning('Start Date field [' + params.startField + '] is multivalued - the first date will be used.');
            }
            if (detectedMultivaluedEnd) {
              notify.warning('End Date field [' + params.endField + '] is multivalued - the first date will be used.');
            }

          }

          updateTimeline(index, events);

          return searchSource.onResults().then(onResults);

        }).catch(notify.error);
      };

      var initGroups = function () {
        initTimeline();

        var groups = [];
        if ($scope.groupsOnSeparateLevels === true) {
          _.each($scope.groups, function (group, index) {
            groups.push({
              id: index,
              content: group.label,
              style: 'background-color:' + group.color + '; color: #fff;'
            });
          });
        } else {
          // single group
          // - a bit of hack but currently the only way I could make it work
          groups.push({
            id: 0,
            content: '',
            style: 'background-color: none;'
          });
        }
        var dataGroups = new vis.DataSet(groups);
        timeline.setGroups(dataGroups);
      };


      $scope.$watch('options', function (newOptions, oldOptions) {
        if (!newOptions || newOptions === oldOptions) {
          return;
        }
        initTimeline();
        timeline.setOptions(newOptions);
        timeline.redraw();
      }, true); // has to be true in other way the change in height is not detected


      $scope.$watch(
        function ($scope) {
          // here to make a comparison use all properties except a searchSource as it was causing angular to
          // enter an infinite loop when trying to determine the object equality
          var arr =  _.map($scope.groups, function (g) {
            return _.omit(g, 'searchSource');
          });

          arr.push($scope.groupsOnSeparateLevels);
          return arr;
        },
        function (newValue, oldValue) {
          if (newValue === oldValue) {
            return;
          }
          initTimeline();
          if ($scope.groups) {
            initGroups();
            // do not use newValue as it does not have searchSource as we filtered it out
            _.each($scope.groups, initSingleGroup);
            courier.fetch();
          }
        },
        true
      );


      $element.on('$destroy', function () {
        _.each($scope.groups, function(group) {
          requestQueue.markAllRequestsWithSourceIdAsInactive(group.searchSource._id);
        });
        if (timeline) {
          timeline.off('select', onSelect);
        }
      });
    } // end of link function


  });
});
