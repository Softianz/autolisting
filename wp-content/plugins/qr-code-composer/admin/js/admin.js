(function($) {
	
	$(window).scroll(function() {
    if ($(window).scrollTop() > 200) {
             $('.bulptinyprowarp').fadeIn(300);
    }
});
	
	
jQuery(document).ready(function($) {

  // Suffix that will be used on the classes of the content divs.
  var tab_suffix = '-tab';

  // Not necessary, just to enable people to choose whatever tab_suffix they want.
  function escape_regexp(str) {
    return str.replace(/[\-\[\]\/\{\}\(\)\*\+\?\.\\\^\$\|]/g, "\\$&");
  }

  // Get the class ending with tab_suffix from an element.
  function get_tab_name_from_class(el) {
    var tab_class_pattern = new RegExp('\\S*' + escape_regexp(tab_suffix));
    if ($(el) && $(el).attr('class')) {
      return $(el).attr('class').match(tab_class_pattern)[0];
    }
  }

  // Update the dom with the selected tab.
  function hash_content_update() {

    var active_section,
      tab_names;

    // Get all classes ending with -tab from div elements directly inside .tab-content.
    tab_names = $('div.tab-content > div').map(function() {
      var tab_name = get_tab_name_from_class($(this));
      if (tab_name) {
        return tab_name.split(tab_suffix)[0];
      }
    }).get();

    if (tab_names.length > 0) {

      // Show first tab initially.
      active_section = tab_names[0];

      // Check if the url hash matches one of the tab names.
      if (document.location.href.split('#')[1] && tab_names.indexOf(document.location.href.split('#')[1]) > -1) {
        active_section = document.location.href.split('#')[1];
      }
      // Handle tab contents.
      $('div.tab-content div.active').removeClass('active');
      $('div.tab-content div.' + active_section + tab_suffix).addClass('active');

      // Handle tab menu.
      $('div.tab-nav ul li.active').removeClass('active');
      $('div.tab-nav ul li a[href="#' + active_section + '"]').closest('li').addClass('active');

    }

  }

  // Set listener for hashchange
  $(window).bind('hashchange', function() {
    hash_content_update();
  });

  // Run the initial content update.
  hash_content_update();

});

}(jQuery));