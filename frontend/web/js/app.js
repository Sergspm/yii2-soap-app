'use strict';
/* global $ */
$(function () {
    $('#calc-form').on('beforeSubmit', function () {
        var form = $(this),
            alerts = $('.alerts');

        $.ajax({
            type: form.attr('method'),
            url: form.attr('action'),
            data: form.serializeArray()
        })
            .done(function (data) {
                alerts.html(data.error
                    ? '<div class="alert alert-danger">' + data.error + '</div>'
                    : '<div class="alert alert-success"><strong>Price: </strong>' + data.price +
                    '<br><strong>Info: </strong>' + data.info +
                    '</div>'
                );
            })
            .fail(function (err) {
                console.log(err);
            });

        return false;
    })
});
