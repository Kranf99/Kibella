define(function (require) {

  require('./kibi_timeline_vis.less');
  require('./kibi_timeline_vis_controller');
  require('./kibi_timeline_vis_params');

  require('ui/registry/vis_types').register(KibiTimelineVisProvider);

  function KibiTimelineVisProvider(Private) {
    var TemplateVisType = Private(require('ui/template_vis_type/TemplateVisType'));
    var Schemas = Private(require('ui/Vis/Schemas'));

    // return the visType object, which kibana will use to display and configure new
    // Vis object of this type.
    return new TemplateVisType({
      name: 'kibi_timeline',
      title: 'Timeline',
      icon: 'fa-clock-o',
      description: 'Timeline widget for visualization of events',
      template: require('plugins/kibi_timeline_vis/kibi_timeline_vis.html'),
      params: {
        defaults: {
          groups: [],
          groupsOnSeparateLevels: false
        },
        editor: '<kibi-timeline-vis-params></kibi-timeline-vis-params>'
      },
      defaultSection: 'options',
      requiresSearch: false
    });
  };
});
