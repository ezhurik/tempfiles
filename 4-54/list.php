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
    <?php
    if ($currentDate != '') {
        echo "<script>" . "var todaysDate = " . $currentDate . "</script>";
    }
    ?>
    <script>

        $(document).ready(function () {

            $('#calendar').fullCalendar({
                defaultDate: todaysDate,
                editable: true,
                eventLimit: true,
                eventClick: function (info) {
                    console.log(info);
                    $("#eventTitle").val(info.title);
                    $("#startDate").val(moment(info.fullStartDate).format("YYYY-MM-DD HH:mm"));
                    $("#endDate").val(moment(info.fullEndDate).format("YYYY-MM-DD HH:mm"));
                    $("#eventId").val(info.id);
                    $("#eventDelete").css("display", "");
                    $("#addModalButton").trigger("click");
                },
                dayClick: function (info) {
                    // console.log(info);
                    // console.log(moment(info._d).format('Y-MM-DD'));
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
                    var eventId = info.id;
                    var startDate = moment(info.start).format('Y-MM-DD');
                    var endDate = moment(info.end).format('Y-MM-DD');
                    jQuery.ajax({
                        type: 'POST',
                        url: BASE_URL + "welcome/updateCalendarEvent",
                        data: {'startDate': startDate, 'endDate': endDate, 'eventId': eventId, 'eventType': 'resize'},
                        success: function (data) {
                            data = JSON.parse(data);
                            if (data.response == "updated")
                                alert("Updated successfully");
                        }
                    });
                },
                eventDrop: function (info) {
                    var movedDate = info.start.toISOString();
                    var eventId = info.id;
                    jQuery.ajax({
                        type: 'POST',
                        url: BASE_URL + "welcome/updateCalendarEvent",
                        data: {'movedDate': movedDate, 'eventId': eventId, 'eventType': 'drop'},
                        success: function (data) {
                            data = JSON.parse(data);
                            if (data.response == "updated")
                                alert("Updated successfully");
                        }
                    });
                },
                events: [
                    <?php for ($i = 0; $i < count($calendarEvents); $i++) { ?>
                    {
                        id: '<?php echo $calendarEvents[$i]['id']; ?>',
                        fullStartDate: '<?php echo $calendarEvents[$i]['fullStartDateTime']; ?>',
                        fullEndDate: '<?php echo $calendarEvents[$i]['fullEndDateTime']; ?>',
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

    </style>
</head>
<body>
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
                    <!--                    <div class="form-group">-->
                    <!--                        <label for="eventDescription">Description</label>-->
                    <!--                        <textarea name="eventDescription" id="eventDescription" class="form-control"></textarea>-->
                    <!--                    </div>-->
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
    // $('.my_dtp_c').datetimepicker('update', new Date())

    $("#eventSubmit").on("click", function (e) {
        e.preventDefault();
        if($("#eventTitle").val()==""){
            alert("Title required");
            return false;
        }
        var formData = $('#addEventForm').serializeArray();
        var ajaxUrl='';
        $hiddenVal=$("#eventId").val();
        if($hiddenVal != ""){
            formData.push({ name: "eventType", value: "eventUpdate" });
            ajaxUrl=BASE_URL + "welcome/updateCalendarEvent";
        }
        else{
            ajaxUrl=BASE_URL + "welcome/addEvent"
        }
        $.ajax({
            type: 'POST',
            url: ajaxUrl,
            // data: $("#addEventForm").serialize(),
            data: formData,
            success: function (data) {
                data = JSON.parse(data);
                if (data.response == "Added") {
                    alert("Event Added Successfully");
                    location.reload();
                }
                else if (data.response == "updated") {
                    alert("Event Updated Successfully");
                    location.reload();
                }
            }
        });
    });

    $('#addEventModal').on('hidden.bs.modal', function () {
        $("#eventId").val('');
    })

    $("#eventDelete").click(function(e){
        e.preventDefault();
        if($("#eventTitle").val()==""){
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
                    location.reload();
                }
            }
        });
    });

</script>
