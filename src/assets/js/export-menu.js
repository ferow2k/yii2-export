(function ($) {

    $.exportMenu = function (element, options) {

        var defaults = {
        };

        var plugin = this;

        plugin.settings = {};

        var $element = $(element),
            $form = $("#" + options.formId),
            $exportRequestParam = $("input[name='" + options.exportRequestParam + "']");

        plugin.init = function () {
            plugin.settings = $.extend({}, defaults, options);

            listen();
        };

        var listen = function () {
            $element.on('click', '.dropdown-menu li, .dropdown-menu .dropdown-item', changeOption);
        };

        var changeOption = function (e) {
            e.preventDefault();

            var selected = $(e.currentTarget).data('id');
            $exportRequestParam.val(selected);

            $form.trigger('submit');
        };

        plugin.init();

    }

    $.fn.exportMenu = function (options) {
        return this.each(function () {
            if (undefined == $(this).data('exportMenu')) {
                var plugin = new $.exportMenu(this, options);
                $(this).data('exportMenu', plugin);
            }
        });
    }

})(jQuery);