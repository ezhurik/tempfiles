<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'/>
    <link href='<?= base_url('assets/fullcalendar.min.css') ?>' rel='stylesheet'/>
    <link href='<?= base_url('assets/fullcalendar.print.min.css') ?>' rel='stylesheet' media='print'/>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/bootstrap-datetimepicker.css') ?>">

    <script src='<?= base_url('assets/jquery.min.js') ?>'></script>

    <script src='<?= base_url('assets/moment.min.js') ?>'></script>
    <script src='<?= base_url('assets/fullcalendar.min.js') ?>'></script>
    <!-- Popper JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

    <script src="<?= base_url('assets/bootstrap-datetimepicker.js'); ?>"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-mousewheel/3.1.13/jquery.mousewheel.min.js"></script>


    <script>
        var BASE_URL = "<?= base_url(); ?>";
    </script>
    <script>

        $(document).ready(function () {

            $('#calendar').fullCalendar({
                defaultDate: moment(new Date()).format("YYYY-MM-DD"),
                editable: true,
                eventLimit: true,
                displayEventTime: false,
                eventClick: function (info) {
                    $("#eventTitle").val(info.title);
                    if (info.fullStartDate) {
                        $("#startDate").val(moment(info.fullStartDate).format("YYYY-MM-DD HH:mm"));
                        $("#endDate").val(moment(info.fullEndDate).format("YYYY-MM-DD HH:mm"));
                    } else {
                        $("#startDate").val(moment(info.start._i).format("YYYY-MM-DD HH:mm"));
                        $("#endDate").val(moment(info.end._i).format("YYYY-MM-DD HH:mm"));
                    }
                    $("#eventId").val(info.id);
                    $("#eventDelete").css("display", "");
                    $("#addModalButton").trigger("click");
                },
                dayClick: function (info) {
                    var dt1 = new Date();
                    var time1 = dt1.getHours() + ":" + dt1.getMinutes() + ":" + dt1.getSeconds();
                    var dt2 = new Date();
                    var time2 = dt2.getHours() + 1 + ":" + dt2.getMinutes() + ":" + dt2.getSeconds();
                    var startDate = moment(info._d).format('Y-MM-DD') + ' ' + time1;
                    var endDate = moment(info._d).format('Y-MM-DD') + ' ' + time2;
                    $('#startDate').datetimepicker('update', startDate);
                    $('#endDate').datetimepicker('update', endDate);
                    $("#eventDelete").css("display", "none");
                    $("#addModalButton").trigger("click");
                },
                eventResize: function (info) {
                    updateDropNResize(info, 'resize');
                },
                eventDrop: function (info) {
                    updateDropNResize(info, 'drop');
                },
                events: [
                    <?php for ($i = 0; $i < count($calendarEvents); $i++) { ?>
                    {
                        id: '<?php echo $calendarEvents[$i]['id']; ?>',
                        fullStartDate: '<?php echo $calendarEvents[$i]['fullStartDateTime']; ?>',
                        fullEndDate: '<?php echo $calendarEvents[$i]['fullEndDateTime']; ?>',
                        numberOfDays: '<?php echo $calendarEvents[$i]['numberOfDays']; ?>',
                        title: '<?php echo $calendarEvents[$i]['title']; ?>',
                        start: '<?php echo $calendarEvents[$i]['start']; ?>',
                        end: '<?php echo $calendarEvents[$i]['end']; ?>',
                    },
                    <?php } ?>
                ]
            });

        });


    </script>
    <style>

        body {
            margin: 40px 10px;
            padding: 0;
            font-family: "Lucida Grande", Helvetica, Arial, Verdana, sans-serif;
            font-size: 14px;
        }

        #calendar {
            max-width: 900px;
            margin: 0 auto;
        }

        #loader {
            content: '';
            display: block;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.3);
            z-index: 9999 !important;
        }

        .loaderClass {
            height: 200px;
            width: auto;
            display: block;
            margin-left: auto;
            margin-right: auto;
            margin-top: 10%;
        }

    </style>
</head>
<body>

<!-- Image loader -->
<div id='loader' style='display: none;'>
    <img class="loaderClass" src='<?= base_url('assets/images/loader.gif') ?>' width='32px' height='32px'>
</div>
<!-- Image loader -->

<div class='response'></div>

<div id='calendar'></div>

<!-- Button trigger modal -->
<button type="button" class="btn btn-primary" id="addModalButton" data-toggle="modal" data-target="#addEventModal"
        style="display: none">
</button>

<!-- Modal -->
<div class="modal fade" id="addEventModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Event</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form name="addEventForm" id="addEventForm">
                    <input type="hidden" name="eventId" id="eventId" value="">
                    <div class="form-group">
                        <label for="eventTitle">Title</label>
                        <input type="text" class="form-control" id="eventTitle" name="eventTitle">
                    </div>
                    <div class="form-group">
                        <label for="startDate">Start Time</label>
                        <input id="startDate" name="startDate" class="my_dtp_c form-control" size="16" type="text">
                    </div>
                    <div class="form-group">
                        <label for="endDate">End Time</label>
                        <input id="endDate" name="endDate" class="my_dtp_c form-control" size="16" type="text">
                    </div>
                    <button type="submit" class="btn btn-danger" id="eventDelete">Delete</button>
                    <button type="submit" class="btn btn-primary" id="eventSubmit">Submit</button>
                </form>
            </div>

        </div>
    </div>
</div>

</body>
</html>
<script>

    $('.my_dtp_c').datetimepicker({
        format: 'yyyy-mm-dd hh:ii',
        fontAwesome: true,
        wheelViewModeNavigation: true
    })

    $("#eventSubmit").on("click", function (e) {
        e.preventDefault();
        if ($("#eventTitle").val() == "") {
            alert("Title required");
            return false;
        }
        var formData = $('#addEventForm').serializeArray();
        var ajaxUrl = '';
        $hiddenVal = $("#eventId").val();
        if ($hiddenVal != "") {
            formData.push({name: "eventType", value: "eventUpdate"});
            ajaxUrl = BASE_URL + "welcome/updateCalendarEvent";
        } else {
            ajaxUrl = BASE_URL + "welcome/addEvent"
        }
        $.ajax({
            type: 'POST',
            url: ajaxUrl,
            data: formData,
            success: function (data) {
                data = JSON.parse(data);
                if (data.response == "Added") {
                    alert("Event Added Successfully");
                    $('#calendar').fullCalendar('renderEvent', {
                        id: data.eventId,
                        title: $("#eventTitle").val(),
                        start: $("#startDate").val(),
                        end: $("#endDate").val()
                    });
                    $("#addEventModal").modal('hide');
                } else if (data.response == "updated") {
                    alert("Event Updated Successfully");
                    $("#calendar").fullCalendar('removeEvents', $("#eventId").val());

                    $('#calendar').fullCalendar('renderEvent', {
                        id: $("#eventId").val(),
                        title: $("#eventTitle").val(),
                        start: $("#startDate").val(),
                        end: $("#endDate").val()
                    });
                    $("#addEventModal").modal('hide');
                }
            }
        });
    });

    $('#addEventModal').on('hidden.bs.modal', function () {
        // $('#form_id').trigger("reset");
        $('#eventTitle').val('');
        $("#eventId").val('');
    })

    $("#eventDelete").click(function (e) {
        e.preventDefault();
        if ($("#eventTitle").val() == "") {
            alert("Title required");
            return false;
        }
        $.ajax({
            type: 'POST',
            url: BASE_URL + "welcome/deleteEvent",
            data: $("#addEventForm").serialize(),
            success: function (data) {
                data = JSON.parse(data);
                if (data.response == "deleted") {
                    alert("Event Deleted Successfully");
                    $("#calendar").fullCalendar('removeEvents', $("#eventId").val());
                    $("#addEventModal").modal('hide');
                }
            }
        });
    });

    function updateDropNResize(info, eventType) {
        console.log(info);
        var eventId = info.id;
        var startDtae;
        var endDate;
        if (eventType == "resize") {
            startDate = moment(info.start).format('Y-MM-DD');
            endDate = moment(info.end).subtract(1, 'days').format('Y-MM-DD');
        } else if (eventType == "drop") {
            if (info.numberOfDays > 0) {
                startDate = info.start.toISOString();
                var d = info.start._d.toISOString();
                d = moment(d).format('Y-MM-DD');
                endDate = moment(d).add(info.numberOfDays, 'days').format("Y-MM-DD");
            } else {
                var startDate = info.start.toISOString();
                var endDate = info.start.toISOString();
            }
        }
        jQuery.ajax({
            type: 'POST',
            url: BASE_URL + "welcome/updateCalendarEvent",
            data: {'startDate': startDate, 'endDate': endDate, 'eventId': eventId, 'eventType': 'dropNResize'},
            beforeSend: function () {
                $(".ajaxLoader").show();
            },
            success: function (data) {
                data = JSON.parse(data);
                if (data.response == "updated")
                    alert("Updated successfully");
            },
            complete: function () {
                $("#loader").css('display', 'none');
            }
        });
    }

</script>
