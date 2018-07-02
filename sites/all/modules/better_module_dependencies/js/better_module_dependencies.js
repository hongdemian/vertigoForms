(function ($) {

  Drupal.behaviors.betterModuleDependencies = {
    attach: function(context) {
      var modules = $('#system-modules span.better-module-dependencies-name');
      $('#system-modules span.better-module-dependencies-selector').click(function(event) {
        event.preventDefault();
        var moduleName = event.currentTarget.textContent;
        moduleName = moduleName.replace(' (disabled)', '');
        moduleName = moduleName.replace(' (enabled)', '');
        moduleName = moduleName.replace(' (missing)', '');
        if (moduleName) {
          modules.each(function(index) {
            if ($(this).text() == moduleName) {
              var scrollSize = $(this).parents('tr').offset().top;
              var bodyPadding = $('body').css('padding-top') || 0;
              var tableHeader = $(this).parents('table').find('thead').height() || 0;
              var scrollTop = scrollSize - parseFloat(bodyPadding) - tableHeader;
              $('html, body').animate({scrollTop:  scrollTop}, 500);
            }
          });
        }
      });
    }
  };

})(jQuery);
