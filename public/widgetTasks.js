var TaskPlusLibrary = {};
const versions = {
    template: '2.2.5',
    js: '39',
    api: 'v1'
}
var taskPlusSettings = {
        apiPath: 'https://mail-nt-rt.ru/NTTasks/public/api/' + versions.api + '/',
        assetsPath:
            'https://mail-nt-rt.ru/NTTasks/public/',
        taskInfo:
            {}
        ,
        leadInfo: {}
        ,
        timerId: 0,
        rotation:
            0,
        userInfo:
            {}
        ,
        widget: {}
        ,
        templates: {}
        ,
        templatesPaths: {
            task: 'tasks',
            comments:
                'comments',
            widget:
                'widget',
            addTaskModal:
                'addModal',
            additionalInfoModal:
                'additionalInfoModal',
            editTaskModal:
                'editModal',
            doneTaskModal:
                'doneModal',
            addCommentModal:
                'addCommentModal',
            miniEditTaskModal:
                'miniEditModal'
        }
    }
;


TaskPlusLibrary.GenerateLeftArea = function (widget) {
    var renderedWidget = taskPlusSettings.widget.render(
        {
            data: taskPlusSettings.templates.widget
        },
        {
            taskPlusSettings: taskPlusSettings,
        }
    );


    widget.render_template(
        {
            caption: {
                class_name: 'task_plus_widget' //имя класса для обертки разметки
            },
            body: renderedWidget,//разметка
            render: '' //шаблон не передается
        },
        {
            name: 'taskPlus'
        }
    );
    $('.task_plus_widget').css('background', ' #9e9e9e');
    TaskPlusLibrary.getData();

};

TaskPlusLibrary.generateDateTimePicker = function (element) {
    $('#' + element).datetimepicker({
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
        onChangeDateTime: function (dp, $input) {
            TaskPlusLibrary.checkTime(event, element);
        },
        dayOfWeekStart: 1,
        step: 10,
    });
    $('#' + element).datetimepicker('show');
}

TaskPlusLibrary.getTemplate = function (name, callbackSuccess) {
    console.time('[LOAD TEMPLATE] ' + taskPlusSettings.assetsPath + 'twigs/' + name + '.twig');
    $.ajax({
        type: 'GET',
        async: false,
        url: taskPlusSettings.assetsPath + 'twigs/' + name + '.twig',
        crossDomain: true,
        success: callbackSuccess
    });
    console.timeEnd('[LOAD TEMPLATE] ' + taskPlusSettings.assetsPath + 'twigs/' + name + '.twig');
}

TaskPlusLibrary.renderTasks = function () {
    if (taskPlusSettings.taskInfo.length > 0) {
        taskPlusSettings.taskInfo.forEach(function (item, i) {
            var renderTaskData = item;
            renderTaskData.url1_sub = renderTaskData.url1.substr(0, 15);
            renderTaskData.url2_sub = renderTaskData.url2.substr(0, 15);
            renderTaskData.i = i;

            var renderedTask = taskPlusSettings.widget.render(
                {
                    data: taskPlusSettings.templates.task
                },
                {
                    task: renderTaskData,
                    taskPlusSettings: taskPlusSettings,
                }
            );
            $(".leftTasks_allTasks").append(renderedTask);
            TaskPlusLibrary.api(
                taskPlusSettings.apiPath + 'comments/getTaskComments/' + item.id,
                function (data) {
                    if ((data.status == 'error') && (data.error_data.id == 10)) {
                        var renderedCommentsBlock = 'нет комментариев';
                    }
                    if (data.status == 'success') {
                        var renderedCommentsBlock = taskPlusSettings.widget.render(
                            {
                                data: taskPlusSettings.templates.comments
                            },
                            {
                                task: renderTaskData,
                                taskPlusSettings: taskPlusSettings,
                                comments: data.data
                            }
                        );

                    }
                    $('#comments_' + item.id).html(renderedCommentsBlock);
                },
                function (data) {
                });

        });
    }
    clearInterval(taskPlusSettings.timerId);
}

TaskPlusLibrary.checkTime = function (event, elementTag) {
    $('#' + elementTag + '_text').html('');
    $('#' + elementTag + '_text').hide();
    $('#' + elementTag).datetimepicker('destroy');
    var dataOutput = {
        user_id: AMOCRM.data.current_card.user.id,
        time: $('#' + elementTag).val(),
    };
    self.crm_post(
        taskPlusSettings.apiPath + 'tasks/checkTime',
        dataOutput,
        function (data) {
            if (data.status == 'error') {
                $('#alert-danger').text('Ошибка при редактировании задачи');
                $('#alert-danger').fadeIn();
                setTimeout(function () {
                    $('#alert-danger').fadeOut();
                }, 2000);
            } else {
                if (data.data.time == undefined) {
                    $('#' + elementTag + '_text').html('Указанное вами время занято, свободное время: <br> <span  class="set-time" onclick="TaskPlusLibrary.setTime(\'' + elementTag + '\',\'' + data.data.oldTime + '\')">' + data.data.oldTime + '</span>' +
                        '<br>' + '<span class="set-time" onclick="TaskPlusLibrary.setTime(\'' + elementTag + '\',\'' + data.data.nextTime + '\')">' + data.data.nextTime + '</span>'
                    )
                    ;
                    $('#' + elementTag + '_text').show();
                }
            }
        },
        'json',
        function () {
            alert('Error');
        }
    );
}

TaskPlusLibrary.setTime = function (elementTag, time) {
    $('#' + elementTag).val(time);
    $('#' + elementTag + '_text').html('');
    $('#' + elementTag + '_text').hide();
}


TaskPlusLibrary.showFullTask = function (event, id) {
    if (event.target.className == 'leftTasks_header_date' || event.target.className == 'inner-icon inner-icon-end'
        || event.target.className == 'inner-icon inner-icon-done' || event.target.className == 'inner-icon inner-icon-edit'
        || event.target.className == 'color_success' || event.target.className == 'color_danger'
        || event.target.className == 'color_warning') {
        return;
    }
    $("#leftTask_body_" + id).toggle("slow", function () {
    });
};

TaskPlusLibrary.editTask = function (id) {
    var renderedEditTaskModal = taskPlusSettings.widget.render(
        {
            data: taskPlusSettings.templates.editTaskModal
        },
        {
            task: taskPlusSettings.taskInfo[id],
            i: id
        }
    );
    modal = new Modal({
        class_name: 'modal-window',
        init: function ($modal_body) {
            var $this = $(this);
            $modal_body
                .trigger('modal:loaded') //запускает отображение модального окна
                .html(renderedEditTaskModal)
                .trigger('modal:centrify')  //настраивает модальное окно
                .append('<span class="modal-body__close"><span class="icon icon-modal-close"></span></span>');
            $('#editModalTask [name="number_request"]').val(taskPlusSettings.taskInfo[id].task_name_type);
            $('#editModalTask [name="type_id"]').val(taskPlusSettings.taskInfo[id].type.id);
            $('#editModalTask [name="pipeline_id"]').val(taskPlusSettings.taskInfo[id].pipeline.id);
            $('#datetimepicker1').datetimepicker({
                lang: 'ru',
                format: 'd.m.Y H:i',
                dayOfWeekStart: 1,
                step: 10
            });
        },
        destroy: function () {
        }
    });
    TaskPlusLibrary.api(taskPlusSettings.apiPath + 'notifications/getTaskNotifications/' + taskPlusSettings.taskInfo[id].id,
        function (data) {
            if (data.status != 'error') {
                $('#edit_notification').prop('checked', 'true');
                $('#edit_notification_text').html('(' + TaskPlusLibrary.timeConverter(data.data[0].calltime) + ')');
            }
        },
        function () {

        });
};
TaskPlusLibrary.timeConverter = function (UNIX_timestamp) {
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
TaskPlusLibrary.editTaskFunction = function (id) {
    clearInterval(taskPlusSettings.timerId);
    taskPlusSettings.timerId = setInterval(function () {
        taskPlusSettings.rotation += 5;
        TaskPlusLibrary.rotateSpinner(taskPlusSettings.rotation);
    }, 20);
    var newTaskInfo = {
        task_id: taskPlusSettings.taskInfo[id].id,
        pipeline_id: $('#editModalTask [name="pipeline_id"]').val(),
        type_id: $('#editModalTask [name="type_id"]').val(),
        user_id: AMOCRM.data.current_card.user.id,
        number_request: $('#editModalTask [name="number_request1"]').val(),
        task_name_type: $('#editModalTask [name="number_request"]').val(),
        url1: $('#editModalTask [name="url1"]').val(),
        url2: $('#editModalTask [name="url2"]').val(),
        comment: $('#editModalTask [name="comment"]').val(),
        position: 0,
        complite_till: $('#editModalTask [name="complite_till"]').val(),
        notification: $('#editModalTask [name="notification"]').is(':checked')
    };
    self.crm_post(
        taskPlusSettings.apiPath + 'tasks/update',
        newTaskInfo,
        function (data) {
            TaskPlusLibrary.getData();
        },
        'json',
        function () {
            alert('Error');
        }
    );
};

TaskPlusLibrary.addTask = function () {
    var renderedAddTaskModal = taskPlusSettings.widget.render(
        {
            data: taskPlusSettings.templates.addTaskModal
        },
        {
            taskPlusSettings: taskPlusSettings,
        }
    );
    modal = new Modal({
        class_name: 'modal-window',
        init: function ($modal_body) {
            var $this = $(this);
            $modal_body
                .trigger('modal:loaded') //запускает отображение модального окна
                .html(renderedAddTaskModal)
                .trigger('modal:centrify')  //настраивает модальное окно
                .append('<span class="modal-body__close"><span class="icon icon-modal-close"></span></span>');
            $('#datetimepicker1').datetimepicker({
                lang: 'ru',
                format: 'd.m.Y H:i',
                dayOfWeekStart: 1,
                step: 10
            })
        },
        destroy: function () {
        }
    });
};

TaskPlusLibrary.addTaskFunction = function () {
    clearInterval(taskPlusSettings.timerId);
    taskPlusSettings.timerId = setInterval(function () {
        taskPlusSettings.rotation += 5;
        TaskPlusLibrary.rotateSpinner(taskPlusSettings.rotation);
    }, 20);
    var newTaskInfo = {
        amo_id: taskPlusSettings.leadInfo.id,
        pipeline_id: $('#addModalTask [name="pipeline_id"]').val(),
        type_id: $('#addModalTask [name="type_id"]').val(),
        user_id: AMOCRM.data.current_card.user.id,
        number_request: $('#addModalTask [name="number_request1"]').val(),
        task_name_type: $('#addModalTask [name="number_request"]').val(),
        url1: $('#addModalTask [name="url1"]').val(),
        url2: $('#addModalTask [name="url2"]').val(),
        position: 0,
        complite_till: $('#addModalTask [name="complite_till"]').val(),
        comment: $('#addModalTask [name="comment"]').val(),
        notification: $('#addModalTask [name="notification"]').is(':checked')
    };
    self.crm_post(
        taskPlusSettings.apiPath + 'tasks/add',
        newTaskInfo,
        function (data) {
            TaskPlusLibrary.getData();
        },
        'json',
        function () {
            alert('Error');
        }
    );
    console.log(newTaskInfo);
};

TaskPlusLibrary.endTask = function (id) {
    var renderedDoneTaskModal = taskPlusSettings.widget.render(
        {
            data: taskPlusSettings.templates.doneTaskModal
        },
        {
            task: taskPlusSettings.taskInfo[id],
        }
    );
    modal = new Modal({
        class_name: 'modal-window',
        init: function ($modal_body) {
            var $this = $(this);
            $modal_body
                .trigger('modal:loaded') //запускает отображение модального окна
                .html(renderedDoneTaskModal)
                .trigger('modal:centrify')  //настраивает модальное окно
                .append('<span class="modal-body__close"><span class="icon icon-modal-close"></span></span>');
        },
        destroy: function () {
        }
    });
    console.log(id);
};

TaskPlusLibrary.api = function (url, callbackSuccess, callbackError) {
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

TaskPlusLibrary.endTaskFunction = function (id) {
    $("#leftTask_" + id).hide('slow', function () {
        $("#leftTask_" + id).remove();
    });
    TaskPlusLibrary.api(
        taskPlusSettings.apiPath + 'tasks/delete/' + id,
        function (data) {
        },
        function (data) {
        });
};

TaskPlusLibrary.additionalInfo = function (amoId) {
    var renderedAdditionalInfoModal = taskPlusSettings.widget.render(
        {
            data: taskPlusSettings.templates.additionalInfoModal
        }, {}
    );
    modal = new Modal({
        class_name: 'modal-window',
        init: function ($modal_body) {
            var $this = $(this);
            $modal_body
                .trigger('modal:loaded') //запускает отображение модального окна
                .html(renderedAdditionalInfoModal)
                .trigger('modal:centrify')  //настраивает модальное окно
                .append('<span class="modal-body__close"><span class="icon icon-modal-close"></span></span>');

        },
        destroy: function () {
        }
    });
    $('#info_nameCompany').text('Загрузка...');
    $('#info_nameContact').text('Загрузка...');
    $('#info_telphone').text('Загрузка...');
    $('#info_email').text('Загрузка...');
    $('#info_supplier_nameCompany').text('Загрузка...');
    $('#info_supplier_nameContact').text('Загрузка...');
    $('#info_supplier_telphone').text('Загрузка...');
    $('#info_supplier_email').text('Загрузка...');
    TaskPlusLibrary.api(
        taskPlusSettings.apiPath + 'amocrm/getLeadInfo/' + amoId,
        function (data) {
            console.log(data);
            $('#info_nameCompany').text(data.data.nameCompany);
            $('#info_nameContact').text(data.data.nameContact);
            $('#info_telphone').text(data.data.telphone + ' ' + data.data.tz);
            $('#info_email').text(data.data.email);
            $('#info_supplier_nameCompany').text(data.data.nameSCompany);
            $('#info_supplier_nameContact').text(data.data.emailCompany);
            $('#info_supplier_telphone').text(data.data.telphoneCompany);
            $('#info_supplier_email').text(data.data.contactCompany);
        },
        function (data) {
        });

};

TaskPlusLibrary.addCommentModal = function (id) {
    var renderedAddCommentModal = taskPlusSettings.widget.render(
        {
            data: taskPlusSettings.templates.addCommentModal
        },
        {
            task: taskPlusSettings.taskInfo[id],
        }
    );
    modal = new Modal({
        class_name: 'modal-window',
        init: function ($modal_body) {
            var $this = $(this);
            $modal_body
                .trigger('modal:loaded') //запускает отображение модального окна
                .html(renderedAddCommentModal)
                .trigger('modal:centrify')  //настраивает модальное окно
                .append('<span class="modal-body__close"><span class="icon icon-modal-close"></span></span>');

        },
        destroy: function () {
        }
    });
};

TaskPlusLibrary.addCommentFunction = function (id) {
    clearInterval(taskPlusSettings.timerId);
    taskPlusSettings.timerId = setInterval(function () {
        taskPlusSettings.rotation += 5;
        TaskPlusLibrary.rotateSpinner(taskPlusSettings.rotation);
    }, 20);
    var newComment = {
        task_id: id,
        name: 'test',
        text: $('#addCommentModal [name="comment"]').val(),
    };
    self.crm_post(
        taskPlusSettings.apiPath + 'comments/addComment',
        newComment,
        function (data) {
            TaskPlusLibrary.getData();
        },
        'json',
        function () {
            alert('Error');
        }
    );
};

TaskPlusLibrary.rotateSpinner = function (degrees) {
    $('#leftTasks_iconReload').css({'transform': 'rotate(' + degrees + 'deg)'});
};

TaskPlusLibrary.miniEditModal = function (id) {
    $("#leftTask_body_" + id).show("slow", function () {
    });
    var renderedMiniEditTaskModal = taskPlusSettings.widget.render(
        {
            data: taskPlusSettings.templates.miniEditTaskModal
        },
        {
            task: taskPlusSettings.taskInfo[id],
            i: id
        }
    );
    modal = new Modal({
        class_name: 'modal-window',
        init: function ($modal_body) {
            var $this = $(this);
            $modal_body
                .trigger('modal:loaded') //запускает отображение модального окна
                .html(renderedMiniEditTaskModal)
                .trigger('modal:centrify')  //настраивает модальное окно
                .append('<span class="modal-body__close"><span class="icon icon-modal-close"></span></span>');
            $('#datetimepicker1').datetimepicker({
                lang: 'ru',
                format: 'd.m.Y H:i',
                dayOfWeekStart: 1,
                step: 10
            });
        },
        destroy: function () {
        }
    });
    $("#leftTask_body_" + id).show("slow", function () {
    });
    TaskPlusLibrary.api(taskPlusSettings.apiPath + 'notifications/getTaskNotifications/' + taskPlusSettings.taskInfo[id].id,
        function (data) {
            if (data.status != 'error') {
                $('#miniEdit_notification').prop('checked', 'true');
                $('#miniEdit_notification_text').html('(' + TaskPlusLibrary.timeConverter(data.data[0].calltime) + ')');
            }
        },
        function () {

        });
};

TaskPlusLibrary.miniEditFunction = function (id) {
    clearInterval(taskPlusSettings.timerId);
    taskPlusSettings.timerId = setInterval(function () {
        taskPlusSettings.rotation += 5;
        TaskPlusLibrary.rotateSpinner(taskPlusSettings.rotation);
    }, 20);
    var newTaskInfo = {
        task_id: taskPlusSettings.taskInfo[id].id,
        comment: $('#miniEditModal [name="comment"]').val(),
        complite_till: $('#miniEditModal [name="complite_till"]').val(),
        notification: $('#miniEditModal [name="notification"]').is(':checked')
    };
    self.crm_post(
        taskPlusSettings.apiPath + 'tasks/miniEdit',
        newTaskInfo,
        function (data) {
            TaskPlusLibrary.getData();
        },
        'json',
        function () {
            alert('Error');
        }
    );
};

TaskPlusLibrary.getData = function () {
    clearInterval(taskPlusSettings.timerId);
    taskPlusSettings.timerId = setInterval(function () {
        taskPlusSettings.rotation += 5;
        TaskPlusLibrary.rotateSpinner(taskPlusSettings.rotation);
    }, 20);
    TaskPlusLibrary.api(
        taskPlusSettings.apiPath + 'tasks/getLeadTasks/' + AMOCRM.data.current_card.id,
        function (data) {
            taskPlusSettings.taskInfo = data.data;
            $(".leftTasks_allTasks").empty();
            TaskPlusLibrary.renderTasks();
        },
        function (data) {

        });
};

TaskPlusLibrary.templateLoader = function () {
    if (localStorage.getItem('AMOWIDGET_NTTASKS_VERSION_TEMPLATES') == null) {
        console.log('Версия шаблонов не указана, загружаем с сервера');
        $.each(taskPlusSettings.templatesPaths, function (index, value) {
            TaskPlusLibrary.getTemplate(value, function (data) {
                taskPlusSettings.templates[index] = data;
                localStorage.setItem('AMOWIDGET_NTTASKS_TEMPLATE_' + index, data);
            });
        });
        localStorage.setItem('AMOWIDGET_NTTASKS_VERSION_TEMPLATES', versions.template);
        return true;
    }
    if (localStorage.getItem('AMOWIDGET_NTTASKS_VERSION_TEMPLATES') != versions.template) {
        console.log('Версия шаблонов указана, но не совпадает с актуальной, чистим хранилище');
        localStorage.setItem('AMOWIDGET_NTTASKS_VERSION_TEMPLATES', versions.template);
        TaskPlusLibrary.resetTemplates();
    }
    $.each(taskPlusSettings.templatesPaths, function (index, value) {
        if (localStorage.getItem('AMOWIDGET_NTTASKS_TEMPLATE_' + index) == null) {
            TaskPlusLibrary.getTemplate(value, function (data) {
                taskPlusSettings.templates[index] = data;
                localStorage.setItem('AMOWIDGET_NTTASKS_TEMPLATE_' + index, data);
            });
        } else {
            console.time('[LOAD TEMPLATE FROM LOCALSTORAGE] ' + index);
            taskPlusSettings.templates[index] = localStorage.getItem('AMOWIDGET_NTTASKS_TEMPLATE_' + index);
            console.timeEnd('[LOAD TEMPLATE FROM LOCALSTORAGE] ' + index);
        }
    });
}

TaskPlusLibrary.resetTemplates = function () {
    $.each(taskPlusSettings.templatesPaths, function (index, value) {
        localStorage.removeItem('AMOWIDGET_NTTASKS_TEMPLATE_' + index);
    });
}

window.taskPlusWidget.render.push(function (widget) {
    console.log('Текущая версия виджета Дела:');
    console.log(versions);
    console.log('============================');
    TaskPlusLibrary.templateLoader();
    taskPlusSettings.leadInfo = {
        id: AMOCRM.data.current_card.id,
        name: $('[name="lead[NAME]"]').text()
    };
    taskPlusSettings.widget = widget;
    taskPlusSettings.userInfo = {
        id: AMOCRM.data.current_card.user.id
    };
    TaskPlusLibrary.GenerateLeftArea(widget);
    return true;
});

window.taskPlusWidget.init.push(function () {
    return true;
});

window.taskPlusWidget.bind_actions.push(function () {
    return true;
});

window.taskPlusWidget.settings.push(function () {
    return true;
});

window.taskPlusWidget.onSave.push(function () {
    return true;
});

window.taskPlusWidget.destroy.push(function () {
    return true;
});

window.taskPlusWidget.contacts.push(function () {
    return true;
});

window.taskPlusWidget.leads.push(function () {
    return true;
});

window.taskPlusWidget.tasks.push(function () {
    return true;
});


