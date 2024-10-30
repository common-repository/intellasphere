<?php
/**
 * Calender Template
 */
if ( defined( 'REST_REQUEST' ) && REST_REQUEST ) {
 $imgurl = ITSP_INTEGRATION__PLUGIN_URL . '/assets/placeholders/event.jpg';  
echo '<img src='.$imgurl.'  width="100%">';
} else {
?>
<div>
    <div class="container" style="widht:100%">
        <div class="row">
            <div class="col-xs-12">
                <br />
                <div class="bootstrap_modal_full_calendar"></div>
            </div>
        </div>
    </div>
    <script>
        jQuery(document).ready(function () {
            setTimeout(function () {

                jQuery('.bootstrap_modal_full_calendar').fullCalendar({
                    timezone: 'local',
                    displayEventTime: false,
                    header: {
                        left: 'prev,next,today',
                        center: 'title',
                        right: 'month,agendaWeek,agendaDay'
                    },
                    eventClick: function (event, jsEvent, view) {
                        if (event.url) {
                            window.open(event.url);
                            return false;
                        }
                        return false;
                    },
                    events: function (start, end, timezone, callback) {
                        var data = {
                            action: 'get_calender_events',
                            start: start.unix() * 1000,
                            end: end.unix() * 1000
                        };

                        var ajax_url = "<?php echo admin_url('admin-ajax.php'); ?>";
                        jQuery.post(ajax_url, data, function (response) {
                            var response = jQuery.parseJSON(response);
                            callback(response.events);
                        });
                    }
                });
            }, 500);
        });
    </script>
    <style>
        .fc-toolbar .fc-center h2, .fc-day-header, .fc-day-number, .fc-axis.fc-time,
        .fc-axis.fc-widget-content{
            font-family: <?php echo esc_attr($brandkit_fontinfo['fontFamily']); ?>;
            color: <?php echo esc_attr($brandkit_colorpalette['primaryTextColor']); ?>;
            line-height: <?php echo esc_attr($brandkit_fontinfo['lineHeight']); ?>;
        }
        .fc-toolbar .fc-center h2{
            font-size: <?php echo esc_attr($brandkit_fontinfo['h1']); ?>;
        }
        .fc-day-header{
            font-size: <?php echo esc_attr($brandkit_fontinfo['p']); ?>;
            padding:6px 0px !important;
            border-width: <?php echo esc_attr($brandkit_colorpalette['borderWidth']); ?>;
            border-color: <?php echo esc_attr($brandkit_colorpalette['borderColor']); ?> !important;
        }
        .fc-widget-header, .fc-day.fc-widget-content, .fc-widget-content{
            border-width: <?php echo esc_attr($brandkit_colorpalette['borderWidth']); ?>;
            border-color: <?php echo esc_attr($brandkit_colorpalette['borderColor']); ?> !important;
        }
        a.fc-day-grid-event.fc-h-event{
            font-family: <?php echo esc_attr($brandkit_fontinfo['fontFamily']); ?>;
            font-size: <?php echo esc_attr($brandkit_fontinfo['p']); ?>;
            background-color: <?php echo esc_attr($brandkit_colorpalette['buttonBackgroundColor']); ?>;
            color: <?php echo esc_attr($brandkit_colorpalette['buttonTextColor']); ?>;
            border-color: <?php echo esc_attr($brandkit_colorpalette['borderColor']); ?>;
        }
        .fc-corner-left, .fc-next-button, .fc-corner-right, .fc-agendaWeek-button{
            font-family: <?php echo esc_attr($brandkit_fontinfo['fontFamily']); ?>;
            font-size: <?php echo esc_attr($brandkit_fontinfo['p']); ?> !important;
            color: <?php echo esc_attr($brandkit_colorpalette['primaryTextColor']); ?>;
            border-color: <?php echo esc_attr($brandkit_colorpalette['borderColor']); ?>;
            box-shadow:none !important;
            text-shadow:none !important;
        }
        .fc-corner-right, .fc-corner-left{
            background-color: <?php echo esc_attr($brandkit_colorpalette['secondaryBackgroundColor']); ?>;
        }
        .fc-corner-right:hover, .fc-corner-left:hover, .fc-next-button:hover, .fc-agendaWeek-button:hover,
        .fc-corner-right:focus, .fc-corner-left:focus, .fc-next-button:focus, .fc-agendaWeek-button:focus{
            background-color: <?php echo esc_attr($brandkit_colorpalette['buttonBackgroundColor']); ?>;
            font-family: <?php echo esc_attr($brandkit_fontinfo['fontFamily']); ?>;
            font-weight:normal !important;
            border-color: <?php echo esc_attr($brandkit_colorpalette['borderColor']); ?>;
            background-image: none;
        }
        a.fc-day-grid-event.fc-h-event:hover{
            color: <?php echo esc_attr($brandkit_colorpalette['buttonTextColor']); ?>;
        }
        .fc-unthemed .fc-today{
            background-color: <?php echo esc_attr($brandkit_colorpalette['secondaryBackgroundColor']); ?>;
        }
    </style>
</div>
<?php
}
