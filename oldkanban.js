var api_server = 'https://mail-nt-rt.ru/NTTasks/public/';
//var api_server = 'https://tasks.pkirillw.ru/';
$(function () {
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

    $('[data-toggle="tooltip"]').tooltip();
    var kanbanCol = $('.pipeline-body');
    //kanbanCol.css('max-height', (window.innerHeight - 150) + 'px');

    var kanbanColCount = parseInt(kanbanCol.length);
    //$('.container-fluid').css('min-width', (kanbanColCount * 350) + 'px');

    draggableInit();
    $('.card-body').slideToggle();
    $('.inner-icon-more').click(function () {
        var $panelBody = $(this).parent().parent().parent().parent().children('.card-body');
        $panelBody.slideToggle();
    });
    $("#name_lead").change(function () {
        $('#lead_select option').remove();
        var timerId;
        $.ajax({
            type: "GET",
            url: api_server + "getLeads/" + $("#name_lead").val(),
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
            $.each(data, function (key, value) {
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

            $('#processing-modal').modal('toggle'); //before post

            console.log('Source ID: ' + sourceId);
            console.log('Target ID: ' + targetId);
            console.log('Element ID: ' + elementId);
            // Post data
            $.ajax({
                type: "POST",
                url: api_server + "tasks/changePipeline",
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    taskId: elementId.replace(/[^\d;]/g, ''),
                    newPipelineId: targetId.replace(/[^\d;]/g, ''),
                    oldPipelineId: sourceId.replace(/[^\d;]/g, '')
                }
            }).done(function () {
                var element = document.getElementById(elementId);
                children.prepend(element);
                $('#processing-modal').modal('toggle'); // after post
            });
        }

        event.preventDefault();
    });
}

function endTaskModal(id) {
    $.ajax({
        type: 'GET',
        url: api_server + 'tasks/getTaskAPI/' + id,
    }).done(function (data) {
        console.log(data);
        $('#endTask_task_name').text(data.number_request);
        $('#endTask_task_subname').text(data.name.type_name);
    });
    $('#endTask_end_button').attr('onclick','endTask('+id+')');
    $('#endTask').modal('show');
}

function endTask(id) {
    $('#endTask').modal('hide');
    $.ajax({
        type: 'GET',
        url: api_server + 'tasks/endTask/' + id,

    }).done(function () {
        $('#task' + id).remove();
    })
}

function saveText(id) {
    $.ajax({
        type: "POST",
        url: api_server + "tasks/changeText",
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            taskId: id,
            text: $('#comment' + id).val()
        }
    });
}

function editTask(id) {
    $.ajax({
        type: 'GET',
        url: api_server + 'tasks/getTaskAPI/' + id,
    }).done(function (data) {
        console.log(data);
        $('#edit_nameTask_header').text(data.number_request);
        $('#edit_nameTask').text(data.number_request);
        $('#edit_taskId').val(data.id);
        $('#edit_pipelineId').val(data.pipeline.id);
        $('#edit_typeId').val(data.type.id);
        $('#edit_url1').val(data.url1);
        $('#edit_url2').val(data.url2);
        $("#edit_numberRequest [value="+data.task_name_type+"]").attr("selected", "selected");
        //$('#edit_numberRequest').val(data.task_name_type);
        $('#edit_comment').text(data.comment);
        $('#datetimepicker2').val(data.complite_till_format);
        $('#changeStatus').modal('show');
    })

}

function changeTimerTaskId(id) {
    console.log(id);
    $('#task_id_timer').val(id);
}

function searchit(id) {
    console.log('/search/' + id + '/' + $('#searchText').val());
    $(location).attr('href', 'https://mail-nt-rt.ru/NTTasks/public/search/' + id + '/' + $('#searchText').val());

}

function additionalInfo(amoId) {
    $('#additionalInfo').modal('show');
    $('#info_nameCompany').text('Загрузка...');
    $('#info_nameContact').text('Загрузка...');
    $('#info_telphone').text('Загрузка...');
    $('#info_email').text('Загрузка...');
    $.ajax({
        type: 'GET',
        url: api_server + 'getLeadInfo/' + amoId,
    }).done(function (data) {
        console.log(data);
        $('#info_nameCompany').text(data.nameCompany);
        $('#info_nameContact').text(data.nameContact);
        $('#info_telphone').text(data.telphone);
        $('#info_email').text(data.email);

    });
    console.log(amoId)
}