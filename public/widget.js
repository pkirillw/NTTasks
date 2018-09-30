var maswidget = {};
var notificationTimerId = 0;
maswidget.init = function (id) {
    if ($('div[data-entity=mas]').length !== 0) {
        return;
    }
    var counter = 0;
    maswidget.api('https://mail-nt-rt.ru/NTTasks/public/getCounterTasks/' + id,
        function (data) {
            counter = data.counter;
            $('head').append('<link href="https://mail-nt-rt.ru/NTTasks/public/css/styleWidget.css" rel="stylesheet" type="text/css" />');
            var menuItem = '<div class="nav__menu__item" data-entity="mas">\n' +
                '<div class="nav__menu__item__link">\n' +
                '<div class="nav__menu__item__icon icon-mas">\n';
            if (data.counter != 0) {
                menuItem = menuItem + '<span class="js-notifications_counter nav__notifications__counter">' + data.counter + '</span>\n';
            } else {
                menuItem = menuItem + '<span class="js-notifications_counter nav__notifications__counter" style="display: none;">' + data.counter + '</span>\n';
            }

            menuItem = menuItem + '<div class="nav__top__userpic__loader stopped" id="page_change_progress"><span class="spinner-icon"></span></div></div>\n' +
                '<div class="nav__menu__item__title">Дела</div>\n' +
                '</div>\n' +
                '</div>';
            $(menuItem).insertAfter('div.nav__menu__item[data-entity=leads]');
            $('div[data-entity=mas] .nav__menu__item__link').click(function (event) {
                event.preventDefault();
                var timerId = setInterval(function () {
                    if ($('.modal-leave-confirm .modal-body__close').length === 0) {
                        clearInterval(timerId);
                    }
                    $('.modal-leave-confirm .modal-body__close').trigger('click');
                }, 10);

                //var url_old = window.location.href;
                //window.location.href= url_old;
                window.open('https://mail-nt-rt.ru/NTTasks/public/board/' + id, '_blank').focus();

                $('.modal-leave-confirm .modal-body__close').trigger('click');
                // $('div[data-entity=mas]').addClass('nav__menu__item-selected');
            });
        },
        function () {

        });
    console.log(counter);


};
maswidget.api = function (url, callbackSuccess, callbackError) {
    console.time('[API] ' + url);
    $.ajax({
        type: 'GET',
        url: url,
        crossDomain: true,
        dataType: 'json',
        success: callbackSuccess,
        error: callbackError
    });
    console.timeEnd('[API] ' + url);
};

maswidget.generateNotification = function (data) {
    maswidget.api('https://mail-nt-rt.ru/NTTasks/public/api/v1/tasks/get/' + data.task_id,
        function (datatask) {
            var notificationData = {
                type: 'error',
                header: "Уведомление о деле",
                text: datatask.data.name.name + "<br>" + datatask.data.name.type_name + "<br>" + datatask.data.pipeline.name + "<br> Нажмите чтобы перейти",
                date: data.calltime,
                link: "https://novyetechnologii.amocrm.ru/leads/detail/" + datatask.data.amo_id
            };
            AMOCRM.notifications.add_error(notificationData);
            maswidget.api('https://mail-nt-rt.ru/NTTasks/public/api/v1/notifications/readNotification/' + data.id,
                function () {

                },
                function () {

                });
        },
        function () {

        });

};
window.NTTasksLeftTasks.bind_actions.push(function (widget) {
    maswidget.widget = widget;
    maswidget.init(widget.system().amouser_id);
    clearInterval(notificationTimerId);
    notificationTimerId = setInterval(function () {
        maswidget.api('https://mail-nt-rt.ru/NTTasks/public/api/v1/notifications/getUserNotifications/' + widget.system().amouser_id,
            function (data) {
                if (data.data.length > 0) {
                    $.each(data.data, function (index, value) {
                        maswidget.generateNotification(value);
                    });
                }
            },
            function () {

            });
    }, 60000);
    return true;
});