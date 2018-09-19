var apiServer = 'https://mail-nt-rt.ru/NTTasks/public/api/v1/';
var path = 'https://mail-nt-rt.ru/NTTasks/public/';
/*var apiServer = 'https://tasks.pkirillw.ru/api/v1/';
var path = 'https://tasks.pkirillw.ru/public/';*/
var userId = $('meta[name="user_id"]').attr('content');
var mode = 'all';
var flagShowDropdown = false;

$(function () {


    $('body').after($('#overlay'));
    var userId = $('meta[name="user_id"]').attr('content');
    $.ajax({
        type: "GET",
        url: apiServer + "tasks/getUserTasks/" + userId + '/' + mode,
    }).done(function (data) {
        if ((data.status == 'error') && (data.error_data.id == 4)) {
            $("body").addClass("blur");
            $('#overlay').show();
        }
        if (data.status == 'success') {
            renderBoard(data.data.tasks);

        }
    });
    $('#datetimepicker1').datetimepicker({
        lang: 'ru',
        format: 'd.m.Y H:i',
        dayOfWeekStart: 1,
        step: 15
    });
    $('#datetimepicker2').datetimepicker({
        lang: 'ru',
        i18n: {
            ru: {
                months: [
                    'Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август',
                    'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь',],
                dayOfWeek: ["Вс", "Пн", "Вт", "Ср", "Чт", "Пт", "Сб.",],
            }
        },
        format: 'd.m.Y H:i',
        dayOfWeekStart: 1,
        step: 15
    });
    $('#datetimepicker3').datetimepicker({
        lang: 'ru',
        i18n: {
            ru: {
                months: [
                    'Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август',
                    'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь',],
                dayOfWeek: ["Вс", "Пн", "Вт", "Ср", "Чт", "Пт", "Сб.",],
            }
        },
        format: 'd.m.Y H:i',
        dayOfWeekStart: 1,
        step: 15
    });
    $('#datetimepicker4').datetimepicker({
        lang: 'ru',
        i18n: {
            ru: {
                months: [
                    'Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август',
                    'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь',],
                dayOfWeek: ["Вс", "Пн", "Вт", "Ср", "Чт", "Пт", "Сб.",],
            }
        },
        format: 'd.m.Y H:i',
        dayOfWeekStart: 1,
        step: 15
    });
    $('#datetimepicker5').datetimepicker({
        lang: 'ru',
        i18n: {
            ru: {
                months: [
                    'Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август',
                    'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь',],
                dayOfWeek: ["Вс", "Пн", "Вт", "Ср", "Чт", "Пт", "Сб.",],
            }
        },
        format: 'd.m.Y H:i',
        dayOfWeekStart: 1,
        step: 15
    });

    $('[data-toggle="tooltip"]').tooltip();


    $('.card-body').slideToggle();

    $('.dropdown-menu a.dropdown-toggle').on('click', function (e) {
        if (!$(this).next().hasClass('show')) {
            $(this).parents('.dropdown-menu').first().find('.show').removeClass("show");
        }
        var $subMenu = $(this).next(".dropdown-menu");
        $subMenu.toggleClass('show');


        $(this).parents('li.nav-item.dropdown.show').on('hidden.bs.dropdown', function (e) {
            $('.dropdown-submenu .show').removeClass("show");
        });


        return false;
    });

    $("#name_lead").change(function () {
        if ($("#name_lead").val().length < 4) {
            return;
        }
        $('#lead_select option').remove();
        var timerId;
        $.ajax({
            type: "GET",
            url: apiServer + "amocrm/getLeads/" + $("#name_lead").val(),
            beforeSend: function () {
                $('#lead_select').prop('disabled', 'disabled');
                $('#lead_select').hide();

                $('#loaderLeads').show();
                timerId = setInterval(function () {
                    $("#innerLoaderLeads").width($("#innerLoaderLeads").width() + 1 + '%');
                }, 250);
            }
        }).done(function (data) {
            $("#innerLoaderLeads").width('100%');
            $.each(data.data, function (key, value) {
                if (key == 0) {
                    $('input[name="number_request1"]').val(value.name);
                }
                $('#lead_select')
                    .append($("<option></option>")
                        .attr("value", value.id)
                        .text(value.name));
            });
            setTimeout(function () {
                $('#lead_select').prop('disabled', false);
                $('#lead_select').show();
                clearInterval(timerId);
                $('#loaderLeads').hide();
                $("#innerLoaderLeads").width('0%');
            }, 500);

        });
        $('#lead_select').change(function () {
            $('input[name="number_request1"]').val($("#lead_select option:selected").text());
        });
    });

});

/**
 * BOARD
 **/

// Helper
function renderBoard(tasks) {
    $('[draggable=true]').unbind();
    $('.pipeline-body').unbind();
    $('.card').remove();
    $('#pipeline1_count_task').text('0');
    $('#pipeline2_count_task').text('0');
    $('#pipeline3_count_task').text('0');
    $('#pipeline4_count_task').text('0');
    var counters = {
        pipeline1: {
            success: 0,
            danger: 0,
            warning: 0
        },
        pipeline2: {
            success: 0,
            danger: 0,
            warning: 0
        },
        pipeline3: {
            success: 0,
            danger: 0,
            warning: 0
        },
        pipeline4: {
            success: 0,
            danger: 0,
            warning: 0
        },

    }
    $.each(tasks, function (index, value) {
        var htmlData = '<div class="card task border_' + value.type + '" id="task' + value.id + '"\n' +
            '                                    draggable="true">\n' +
            '                                    <div class="card-header row" onclick="showBody(event,' + value.id + ')" style="\n';
        if (value.flag_expired) {
            htmlData = htmlData + 'background: #f9e4e4;\n';
        } else {
            htmlData = htmlData + 'background: #fff;\n';
        }
        htmlData = htmlData +
            '                                            border-bottom: 0px;\n' +
            '                                            padding: 5px 5px 0;\n' +
            '                                            margin-right: 0;\n' +
            '                                            margin-left: 0;' +
            '                                            ">\n' +
            '              <div class="col-sm-10" style="padding: 0;">' +
            '<h5 class="card-title"><a\n' +
            '                                                    class="color_' + value.type + '"\n' +
            '                                                    href="https://novyetechnologii.amocrm.ru/leads/detail/' + value.amo_id + '"\n' +
            '                                                    target="_blank">' + value.name.name + '\n' +
            '                                                <br>' + value.name.type_name + '</a>\n' +
            '                                        </h5>\n' +
            '                                        <h6 class="card-subtitle mb-2 text-muted">\n';
        if (value.flag_expired) {
            htmlData = htmlData + '<img src="' + path + '/images/new/expired-20px.svg" width="18px" style="">';
        }

        htmlData = htmlData + '<span class="datetask" onclick="miniEditModal(event,' + value.id + ');">' + timeConverter(value.complite_till) + '</span>' +
            '                                        </h6>';
        if ((value.url1 != '') || (value.url2 != '')) {
            htmlData = htmlData + '<div id="bodyLinks" style="display: none;font-size: 12px;">\n';
            if (value.url1 != '') {
                htmlData = htmlData + '<a data-toggle="tooltip" data-placement="top"\n' +
                    'href="' + value.url1 + '" target="_blank" title="' + value.url1 + '">' + value.url1 + '</a>';
            }
            if (value.url2 != '') {
                htmlData = htmlData + '<br><a data-toggle="tooltip" data-placement="top"\n' +
                    'href="' + value.url2 + '" target="_blank" title="' + value.url2 + '">' + value.url2 + '</a>';
            }

            htmlData = htmlData + '</div>\n';
        }
        htmlData = htmlData + '</div>\n' +
            '                                        <div class="col-sm-2" style="padding: 0;">\n' +
            '                                            <div class="buttons" style="float: right;\n' +
            '    margin: 5px auto 5px;\n' +
            '    width: 20px;">\n' +
            '                                                <span class="inner-icon inner-icon-edit" data-toggle="tooltip"\n' +
            '                                                      data-placement="top" onclick="editTaskModal(event,' + value.id + ')"\n' +
            '                                                      title="Изменить задачу">\n' +
            '                                                </span>\n' +
            '                                                <span class="inner-icon inner-icon-more" data-toggle="tooltip"\n' +
            '                                                      data-placement="top" \n' +
            '                                                      title="Развернуть">\n' +
            '                                                </span>\n' +
            '                                                <span class="inner-icon inner-icon-done"  style="display: none;" data-toggle="tooltip"\n' +
            '                                                      data-placement="top" onclick="endTaskModal(event,' + value.id + ')"\n' +
            '                                                      title="Завершить задачу">\n' +
            '                                                </span>\n' +
            '                                                <span class="inner-icon inner-icon-end" style="display: none;" data-toggle="tooltip"\n' +
            '                                                      data-placement="top" onclick="additionalInfo(event,' + value.amo_id + ')"\n' +
            '                                                      title="Информация о поставщиках">\n' +
            '                                                </span>\n' +
            '                                            </div>\n' +
            '                                        </div>\n';

        htmlData = htmlData + '                                    </div>\n' +
            '                                    <div class="card-body" data-open="false"  style="padding: 5px 5px; display: none;">\n';
        htmlData = htmlData + '                                    <div id="commentBlock"></div></div>\n' +
            '                                </div>\n';
        $('#pipeline' + value.pipeline_id).append(htmlData);
        counters['pipeline' + value.pipeline_id][value.type] = counters['pipeline' + value.pipeline_id][value.type] + 1;

        $('#pipeline' + value.pipeline_id + '_count_task').text(parseInt($('#pipeline' + value.pipeline_id + '_count_task').text()) + 1);
    });
    $('#pipeline1_count_task').html('' +
        '<span class="color_warning">' + counters.pipeline1.warning + '</span>/' +
        '<span class="color_success">' + counters.pipeline1.success + '</span>/' +
        '<span class="color_danger">' + counters.pipeline1.danger + '</span>');
    $('#pipeline2_count_task').html('' +
        '<span class="color_warning">' + counters.pipeline2.warning + '</span>/' +
        '<span class="color_success">' + counters.pipeline2.success + '</span>/' +
        '<span class="color_danger">' + counters.pipeline2.danger + '</span>');
    $('#pipeline3_count_task').html('' +
        '<span class="color_warning">' + counters.pipeline3.warning + '</span>/' +
        '<span class="color_success">' + counters.pipeline3.success + '</span>/' +
        '<span class="color_danger">' + counters.pipeline3.danger + '</span>');
    $('#pipeline4_count_task').html('' +
        '<span class="color_warning">' + counters.pipeline4.warning + '</span>/' +
        '<span class="color_success">' + counters.pipeline4.success + '</span>/' +
        '<span class="color_danger">' + counters.pipeline4.danger + '</span>');
    draggableInit();

}

function draggableInit() {
    var sourceId;

    $('[draggable=true]').bind('dragstart', function (event) {
        sourceId = $(this).parent().attr('id');
        event.originalEvent.dataTransfer.setData("text/plain", event.target.getAttribute('id'));
    });

    $('.pipeline-body').bind('dragover', function (event) {
        event.preventDefault();
    });

    $('.pipeline-body').bind('drop', function (event) {
        var children = $(this);
        var targetId = children.attr('id');

        if (sourceId != targetId) {
            var elementId = event.originalEvent.dataTransfer.getData("text/plain");

            console.log('Source ID: ' + sourceId);
            console.log('Target ID: ' + targetId);
            console.log('Element ID: ' + elementId);
            // Post data
            $.ajax({
                type: "POST",
                url: apiServer + "tasks/updatePipeline",
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    taskId: elementId.replace(/[^\d;]/g, ''),
                    newPipelineId: targetId.replace(/[^\d;]/g, ''),
                    oldPipelineId: sourceId.replace(/[^\d;]/g, '')
                }
            }).done(function (data) {
                if (data.status == 'error') {
                    console.log(data);
                    $('#alert-danger').text('Ошибка при переносе задачи');
                    $('#alert-danger').fadeIn();
                    setTimeout(function () {
                        $('#alert-danger').fadeOut();
                    }, 900);
                } else {
                    $('#' + sourceId + '_count_task').text(parseInt($('#' + sourceId + '_count_task').text()) - 1);
                    $('#' + targetId + '_count_task').text(parseInt($('#' + targetId + '_count_task').text()) + 1);
                    $('#alert-success').text('Задача перенесена');
                    $('#alert-success').fadeIn();
                    setTimeout(function () {
                        $('#alert-success').fadeOut();
                    }, 900);
                }
                var element = document.getElementById(elementId);
                children.prepend(element);
            });
        }

        event.preventDefault();
    });
}

function showBody(event, id) {
    if (event.target.className == 'datetask' || event.target.className == 'inner-icon inner-icon-end'
        || event.target.className == 'inner-icon inner-icon-done' || event.target.className == 'inner-icon inner-icon-edit'
        || event.target.className == 'color_success' || event.target.className == 'color_danger'
        || event.target.className == 'color_warning') {
        return;
    }
    var $panelBody = $('#task' + id).children('.card-body');
    $('#task' + id).children('.card-header').children('.col-sm-2').children('.buttons').children('.inner-icon-done').slideToggle();
    $('#task' + id).children('.card-header').children('.col-sm-2').children('.buttons').children('.inner-icon-end').slideToggle();
    $('#task' + id).children('.card-header').children('.col-sm-10').children('#bodyLinks').slideToggle();
    $panelBody.attr('data-open', $($panelBody).is(":hidden"));
    if ($($panelBody).is(":hidden")) {
        renderComment(id);
    }
    $panelBody.slideToggle();
}

function timeConverter(UNIX_timestamp) {
    var a = new Date(UNIX_timestamp * 1000);
    var months = ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'];
    var year = a.getFullYear();
    var month = months[a.getMonth()];
    var date = a.getDate();
    var hour = a.getHours();
    var min = a.getMinutes();
    if (hour < 10) {
        hour = '0' + hour;
    }
    if (min < 10) {
        min = '0' + min;
    }
    if (date < 10) {
        date = '0' + date;
    }
    var time = date + '.' + month + '.' + year + ' ' + hour + ':' + min;
    return time;
}

function searchit() {
    changeMode('search|' + $('#searchText').val());
}

function changeMode(inputMode) {
    mode = inputMode;
    var userId = $('meta[name="user_id"]').attr('content');
    $.ajax({
        type: "GET",
        url: apiServer + "tasks/getUserTasks/" + userId + '/' + mode,
    }).done(function (data) {
        if ((data.status == 'error') && (data.error_data.id == 4)) {
            $("body").addClass("blur");
            $('#overlay').show();
        }
        if (data.status == 'success') {
            renderBoard(data.data.tasks);

        }
    });
}

function setIntervalMenu() {
    changeMode('interval|' + $('#datetimepicker4').val() + '|' + $('#datetimepicker5').val());
    $('#setIntervalMenuModal').modal('hide');
}

function checkTime(elementTag) {
    $('#' + elementTag + '_text').html('');
    $('#' + elementTag + '_text').hide();
    var dataOutput = {
        user_id: $('meta[name="user_id"]').attr('content'),
        time: $('#' + elementTag).val(),
    };

    $.ajax({
        type: 'POST',
        url: apiServer + 'tasks/checkTime',
        data: dataOutput,
    }).done(function (data) {
        if (data.status == 'error') {
            $('#alert-danger').text('Ошибка при редактировании задачи');
            $('#alert-danger').fadeIn();
            setTimeout(function () {
                $('#alert-danger').fadeOut();
            }, 2000);
        } else {
            if (data.data.time == undefined) {
                $('#' + elementTag + '_text').html('Указанное вами время занято, свободное время: <br> ' + data.data.oldTime + '<br>' + data.data.nextTime);
                $('#' + elementTag + '_text').show();
            }
        }
    });
}

function openAdditionalMenuSearch() {
    if (flagShowDropdown) {
        $(".dropdown").removeClass('open');
        flagShowDropdown = false;
    } else {
        $(".dropdown").addClass('open');
        flagShowDropdown = true;
    }
    return false;
}

// Modals
function addTaskModal() {
    $('#lead_select').hide();
    $('#addTask_pipeline_id').val('');
    $('#name_lead').val('');
    $('#addTask_type_id').val('');
    $('#lead_select').val('');
    $('#addTask_number_request1').val('');
    $('#addTask_number_request').val('');
    $('#addTask_url1').val('');
    $('#addTask_url2').val('');
    $('#addTask_comment').val('');
    $("body").removeClass("blur");
    $('#addTask_notification').prop('checked', false);
    $('#overlay').hide();
}

function editTaskModal(event, id) {
    event.preventDefault();
    $('#edit_notification').prop('checked', false);
    $('#edit_notification_text').html('');
    $.ajax({
        type: 'GET',
        url: apiServer + 'tasks/get/' + id,
    }).done(function (data) {
        if (data.status == 'error') {
            console.log(data);
            $('#alert-danger').text('Ошибка при получении данных о задаче');
            $('#alert-danger').fadeIn();
            setTimeout(function () {
                $('#alert-danger').fadeOut();
            }, 2000);
        } else {
            $('#edit_nameTask_header').text(data.data.number_request);
            $('#edit_nameTask').text(data.data.number_request);
            $('#edit_taskId').val(data.data.id);
            $('#edit_pipelineId').val(data.data.pipeline.id);
            $('#edit_typeId').val(data.data.type.id);
            $('#edit_url1').val(data.data.url1);
            $('#edit_url2').val(data.data.url2);
            $("#edit_numberRequest [value=" + data.data.task_name_type + "]").attr("selected", "selected");
            //$('#edit_numberRequest').val(data.task_name_type);
            // $('#edit_comment').text(data.data.comment);
            $('#datetimepicker2').val(data.data.complite_till_format);
            $('#changeStatus').modal('show');
        }
    });
    $.ajax({
        type: 'GET',
        url: apiServer + 'notifications/getTaskNotifications/' + id,
    }).done(function (data) {
        if (data.status !== 'error') {
            console.log(data);
            $('#edit_notification').prop('checked', true);
            $('#edit_notification_text').html('(' + timeConverter(data.data[0].calltime) + ')');
        }
    });
}

function endTaskModal(event, id) {
    $.ajax({
        type: 'GET',
        url: apiServer + 'tasks/get/' + id,
    }).done(function (data) {
        if (data.status == 'error') {
            console.log(data);
            $('#alert-danger').text('Ошибка при получении данных о задаче');
            $('#alert-danger').fadeIn();
            setTimeout(function () {
                $('#alert-danger').fadeOut();
            }, 2000);
        } else {
            $('#endTask_task_name').text(data.data.number_request);
            $('#endTask_task_subname').text(data.data.name.type_name);
        }
    });
    $('#endTask_end_button').attr('onclick', 'endTask(' + id + ')');
    $('#endTask').modal('show');
}

function additionalInfo(event, amoId) {
    $('#additionalInfo').modal('show');
    $('#info_nameCompany').text('Загрузка...');
    $('#info_nameContact').text('Загрузка...');
    $('#info_telphone').text('Загрузка...');
    $('#info_email').text('Загрузка...');
    $.ajax({
        type: 'GET',
        url: apiServer + 'amocrm/getLeadInfo/' + amoId,
    }).done(function (data) {
        if (data.status == 'error') {
            console.log(data);
            $('#alert-danger').text('Ошибка при получении данных о сделке');
            $('#alert-danger').fadeIn();
            setTimeout(function () {
                $('#alert-danger').fadeOut();
            }, 2000);
        } else {
            $('#info_nameCompany').text(data.data.nameCompany);
            $('#info_nameContact').text(data.data.nameContact);
            $('#info_telphone').text(data.data.telphone + ' ' + data.data.tz);
            $('#info_email').text(data.data.email);
        }

    });
}

function miniEditModal(event, id) {
    $('#miniEdit_notification').prop('checked', false);
    $('#miniEdit_notification_text').html('');
    $('#miniEdit_comment').val('');
    $.ajax({
        type: 'GET',
        url: apiServer + 'tasks/get/' + id,
    }).done(function (data) {
        if (data.status == 'error') {
            console.log(data);
            $('#alert-danger').text('Ошибка при получении данных о задаче');
            $('#alert-danger').fadeIn();
            setTimeout(function () {
                $('#alert-danger').fadeOut();
            }, 2000);
        } else {
            $('#miniEdit_nameTask_header').text(data.data.number_request);
            $('#miniEdit_taskId').val(data.data.id);
            $('#datetimepicker3').val(data.data.complite_till_format);
            $('#miniEdit').modal('show');
        }
    });
    $.ajax({
        type: 'GET',
        url: apiServer + 'notifications/getTaskNotifications/' + id,
    }).done(function (data) {
        if (data.status != 'error') {
            if (data.status != 'error') {
                $('#miniEdit_notification').prop('checked', true);
                $('#miniEdit_notification_text').html('(' + timeConverter(data.data[0].calltime) + ')');
            }
        }
    });
}

function setIntervalMenuModal() {
    $('#setIntervalMenuModal').modal('show');
}

function showNotificationModal() {
    $('#notification_modal_active_body').html('');
    $('#notification_modal_expired_body').html('');

    var userId = $('meta[name="user_id"]').attr('content');
    $.ajax({
        type: "GET",
        url: apiServer + "notifications/getAllUserNotifications/" + userId,
    }).done(function (data) {
        if (data.status == 'error') {
            console.log(data);
            $('#alert-danger').text('Ошибка при загрузке уведомлений');
            $('#alert-danger').fadeIn();
            setTimeout(function () {
                $('#alert-danger').fadeOut();
            }, 900);
        }
        if (data.status == 'success') {
            var index_active = 1;
            var index_expired = 1;
            $.each(data.data, function (index, value) {
                if (value.notification.status == 0) {
                    var selectedTable = 'active';
                    var counter = index_active;
                    index_active = index_active + 1;
                    if (value.notification.calltime < Math.floor(Date.now() / 1000)) {
                        var status = 'Ожидает прочтения';
                    } else {
                        var status = 'Ожидает отправки';
                    }
                } else {
                    var selectedTable = 'expired';
                    var counter = index_expired;
                    var status = 'Отправлено';
                    index_expired = index_expired + 1;
                }
                htmlData =
                    '<tr>' +
                    '   <td>' + counter + '</td>' +
                    '   <td>' + value.task.number_request + ', ' + value.task.name.type_name + '</td>' +
                    '   <td>' + status + '</td>' +
                    '   <td>' + timeConverter(value.notification.calltime) + '</td>' +
                    '</tr>';
                $('#notification_modal_' + selectedTable + '_body').append(htmlData);
            });
            $('#notificationModal').modal('show');
        }
    });
}

/**
 * TASKS
 **/

function addTask() {
    var dataOutput = {
        pipeline_id: $('#addTask_pipeline_id').val(),
        type_id: $('#addTask_type_id').val(),
        user_id: $('#addTask_user_id').val(),
        amo_id: $('#lead_select').val(),
        number_request: $('#addTask_number_request1').val(),
        task_name_type: $('#addTask_number_request').val(),
        url1: $('#addTask_url1').val(),
        url2: $('#addTask_url2').val(),
        position: 0,
        notification: $('#addTask_notification').is(':checked'),
        complite_till: $('#datetimepicker1').val(),
        comment: $('#addTask_comment').val()
    };

    $.ajax({
        type: 'POST',
        url: apiServer + 'tasks/add',
        data: dataOutput,
    }).done(function (data) {
        if (data.status == 'error') {
            $('#alert-danger').text('Ошибка при создании задачи');
            $('#alert-danger').fadeIn();
            setTimeout(function () {
                $('#alert-danger').fadeOut();
            }, 2000);
        } else {
            $.ajax({
                type: "GET",
                url: apiServer + "tasks/getUserTasks/" + data.data.user_id + '/' + mode,
            }).done(function (data) {
                if ((data.status == 'error') && (data.error_data.id == 4)) {
                    $("body").addClass("blur");
                    $('#overlay').show();
                }
                if (data.status == 'success') {
                    renderBoard(data.data.tasks);

                }
            });

        }
    });
    $('#createTask').modal('hide');
}

function editTask() {
    var dataOutput = {
        task_id: $('#edit_taskId').val(),
        pipeline_id: $('#edit_pipelineId').val(),
        type_id: $('#edit_typeId').val(),
        user_id: $('#edit_userId').val(),
        task_name_type: $('#edit_numberRequest').val(),
        url1: $('#edit_url1').val(),
        url2: $('#edit_url2').val(),
        comment: $('#edit_comment').val(),
        position: 0,
        notification: $('#edit_notification').is(':checked'),
        complite_till: $('#datetimepicker2').val(),
    };

    $.ajax({
        type: 'POST',
        url: apiServer + 'tasks/update',
        data: dataOutput,
    }).done(function (data) {
        if (data.status == 'error') {
            $('#alert-danger').text('Ошибка при редактировании задачи');
            $('#alert-danger').fadeIn();
            setTimeout(function () {
                $('#alert-danger').fadeOut();
            }, 2000);
        } else {
            $.ajax({
                type: "GET",
                url: apiServer + "tasks/getUserTasks/" + data.data.user_id + '/' + mode,
            }).done(function (data) {
                if ((data.status == 'error') && (data.error_data.id == 4)) {
                    $("body").addClass("blur");
                    $('#overlay').show();
                }
                if (data.status == 'success') {
                    renderBoard(data.data.tasks);

                }
            });
        }
    });
    $('#changeStatus').modal('hide');
}

function endTask(id) {
    $('#endTask').modal('hide');
    $.ajax({
        type: 'GET',
        url: apiServer + 'tasks/delete/' + id,
    }).done(function (data) {
        if (data.status == 'error') {
            $('#alert-danger').text('Ошибка при редактировании задачи');
            $('#alert-danger').fadeIn();
            setTimeout(function () {
                $('#alert-danger').fadeOut();
            }, 2000);
        } else {
            $.ajax({
                type: "GET",
                url: apiServer + "tasks/getUserTasks/" + data.data.user_id + '/' + mode,
            }).done(function (data) {
                if ((data.status == 'error') && (data.error_data.id == 4)) {
                    $("body").addClass("blur");
                    $('#overlay').show();
                }
                if (data.status == 'success') {
                    renderBoard(data.data.tasks);

                }
            });
        }
    })
}

function miniEdit() {
    var dataOutput = {
        task_id: $('#miniEdit_taskId').val(),
        comment: $('#miniEdit_comment').val(),
        complite_till: $('#datetimepicker3').val(),
        notification: $('#miniEdit_notification').is(':checked'),
    };

    $.ajax({
        type: 'POST',
        url: apiServer + 'tasks/miniEdit',
        data: dataOutput,
    }).done(function (data) {
        if (data.status == 'error') {
            $('#alert-danger').text('Ошибка при редактировании задачи');
            $('#alert-danger').fadeIn();
            setTimeout(function () {
                $('#alert-danger').fadeOut();
            }, 2000);
        } else {
            $.ajax({
                type: "GET",
                url: apiServer + "tasks/getUserTasks/" + data.data.user_id + '/' + mode,
            }).done(function (data) {
                if ((data.status == 'error') && (data.error_data.id == 4)) {
                    $("body").addClass("blur");
                    $('#overlay').show();
                }
                if (data.status == 'success') {
                    renderBoard(data.data.tasks);

                }
            });
        }
    });
    $('#miniEdit').modal('hide');
}

/**
 * COMMENTS
 **/

function renderComment(id) {
    var $panelBody = $('#task' + id).children('.card-body').children('#commentBlock');

    $.ajax({
        type: "GET",
        url: apiServer + "comments/getTaskComments/" + id,
        beforeSend: function () {
            $panelBody.html('<div class="progress" id="loaderComment">\n' +
                '  <div class="progress-bar" role="progressbar" id="innerLoaderComment" style="width: 0%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>\n' +
                '</div>');
            notificationTimerId = setInterval(function () {
                $("#innerLoaderComment").width($("#innerLoaderComment").width() + 1 + '%');
            }, 250);
        }
    }).done(function (data) {
        $("#innerLoaderComment").width('100%');
        setTimeout(function () {

            clearInterval(notificationTimerId);
            $('#loaderComment').hide();
            $("#innerLoaderComment").width('0%');
        }, 500);
        var html_data = '<p id="addCommentButton" style="\n' +
            '    font-size: 12px;\n' +
            '    margin-bottom: 2.5px;\n' +
            '    padding-bottom: 2.5px;\n' +
            '    text-align: center;\n' +
            '" onclick="addCommentModal(' + id + ')"> + добавить комментарий</p>';
        if ((data.status == 'error') && (data.error_data.id == 10)) {
            html_data = html_data + '<p class="text-center">В данной задаче нет комментариев</p>';
            $panelBody.html(html_data);
        }
        if (data.status == 'success') {
            $.each(data.data, function (index, value) {
                html_data = html_data + '' +
                    '<div style="\n' +
                    '    font-size: 12px;\n' +
                    '    margin-top: 3.5px;\n' +
                    '    padding-top: 0.5px;\n' +
                    '    margin-bottom: 0;\n' +
                    '    border-radius: 15px;\n' +
                    '    border-top-right-radius: unset;\n' +
                    '    background: #eee;\n' +
                    '"> ' +
                    '<span class="commentDate" style="\n' +
                    '    padding: 0px 5px 0 0;\n' +
                    '    float: right;\n' +
                    '    font-weight: 700;\n' +
                    '">' + value.created_at + '</span>'
                    + '<p style="\n' +
                    '    padding: 0px 10px 2px;\n' +
                    '    margin-bottom: 0.25px;\n' +
                    '">' + value.text + '</p>' +
                    '</div>'
            });
            $panelBody.html(html_data);
        }
    });
}

function addCommentModal(id) {
    text: $('#addComment_comment').val('');
    $.ajax({
        type: 'GET',
        url: apiServer + 'tasks/get/' + id,
    }).done(function (data) {
        if (data.status == 'error') {
            $('#alert-danger').text('Ошибка при получении данных о задаче');
            $('#alert-danger').fadeIn();
            setTimeout(function () {
                $('#alert-danger').fadeOut();
            }, 2000);
        } else {
            $('#addComment_nameTask_header').text(data.data.number_request);
            $('#addComment_taskId').val(id);
        }
    });
    $('#addComment').modal('show');
}

function addComment() {
    var dataOutput = {
        task_id: $('#addComment_taskId').val(),
        name: 'test',
        text: $('#addComment_comment').val()
    };

    $.ajax({
        type: 'POST',
        url: apiServer + 'comments/addComment',
        data: dataOutput,
    }).done(function (data) {
        if (data.status == 'error') {
            $('#alert-danger').text('Ошибка при создании комментария');
            $('#alert-danger').fadeIn();
            setTimeout(function () {
                $('#alert-danger').fadeOut();
            }, 2000);
        } else {
            renderComment(dataOutput.task_id);
        }
    });
    $('#addComment').modal('hide');
}