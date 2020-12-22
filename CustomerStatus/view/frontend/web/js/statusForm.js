define([
    'uiComponent',
    'ko',
    'jquery',
    'mage/url',
], function (Component, ko, $, url) {

    url.setBaseUrl(BASE_URL);
    var linkUrl = url.build('customerstatus/account/status');
    return Component.extend({
        userStatus: ko.observable(),

        initialize: function () {
            this._super();
        },
        sendStatus: function () {
            if(this.userStatus()===undefined || this.userStatus()==='' ) {

                $("#success-message").show();

                $("#success-message").text("Status can't be blank");
                $("#success-message").css("color", "red");


            }
            else{
                return $.ajax(linkUrl, {
                    method: 'POST',
                    data: {
                        status: this.userStatus

                    },
                    showLoader: true
                }).done(function (data) {
                    if (data != null && data.error != null) {
                        alert(data.error)
                        return false;
                    }

                    if (data.success) {
                        $("#success-message").show();
                        $("#customer-status").text(data.label)
                        $("#success-message").text("You've saved your status");
                        $("#success-message").css("color", "green");
                    }
                    let delayInMilliseconds = 2000
                    setTimeout(function () {
                        $("#success-message").hide();
                    }, delayInMilliseconds)



                }).fail(function () {
                    $("#success-message").show();

                    $("#success-message").text("something went wrong!");
                    $("#success-message").css("color", "red");


                }).always(function () {

                });

            }

        }

    });
});
