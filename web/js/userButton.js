var userButton = (function($){
    "use strict";

    function _renderUserButton(options, userData) {
        var $userContainer = $('#' + options.userContainerId);

        var userPagePathTemplate = options.userPagePath;
        var userPagePath = userPagePathTemplate.replace('1', userData.id);
        
        
        var htmlUserContainer = '';
        var htmlUserTemplate = $('#' + options.userContainerTemplateId).html();
        
        if(userData.loggedIn && userData.isAdmin === false) {
            htmlUserContainer = htmlUserTemplate
                .replace('__USER_BUTTON__', '#')
                .replace('__DROPDOWN_TOGGLE__', 'dropdown-toggle')
                .replace('__DROPDOWN__', 'dropdown')
                .replace('__USER_NAME__', userData.name)
                .replace('__VISIBILITY__', 'visible')
                .replace('__USER_PAGE__', userPagePath)
                .replace('__USER_TITLE__', 'Stranica korisnika');
        } else if(userData.loggedIn && userData.isAdmin === true) {
            htmlUserContainer = htmlUserTemplate
                .replace('__USER_BUTTON__', '#')
                .replace('__DROPDOWN_TOGGLE__', 'dropdown-toggle')
                .replace('__DROPDOWN__', 'dropdown')
                .replace('__USER_NAME__', userData.name)
                .replace('__VISIBILITY__', 'visible')
                .replace('__USER_PAGE__', options.adminPagePath)
                .replace('__USER_TITLE__', 'Admin stranica');
        } else {
            htmlUserContainer = htmlUserTemplate
                .replace('__USER_BUTTON__', options.loginPath)
                .replace('__DROPDOWN_TOGGLE__', '')
                .replace('__DROPDOWN__"', 'BZV')
                .replace('__USER_NAME__', 'Login')
                .replace('__VISIBILITY__', 'hidden');
        }

        $userContainer.replaceWith(htmlUserContainer);
    }
    /**
     * Poziva Ajax za dohvaćanje korisnika
     * @param objekt options
     */
    function _initializeButton(options) {
        $.ajax({
            type: "GET",
            url: options.userDataPath,
            dataType: "json",
            success: function(userData) {
                _renderUserButton(options, userData);
            }
        });
    }

    return {
        "init": _initializeButton,
    };
})(jQuery);