<?php
session_start();
$schedule = $_SESSION['schedule'];
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Project Schedule</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
    <link rel="stylesheet" href="scheduler_style.css">

</head>

<body>

    <header>
        <h1>Scheduler</h1>
        <a href="home.php" id="home-btn" class="home-button">Home</a>
    </header>

    <main>

        <div style="schedule-view;">
            <h2>Current Schedule</h2>
            <table>
                <thead>
                    <tr>
                        <th>Day</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($schedule as $day => $times): ?>
                <?php if ($times['availability']): ?>
                    <tr>
                        <td>
                            <?= htmlspecialchars($day); ?>
                        </td>
                        <td>
                            <?= $times['start'] ? date_format(date_create($times['start']), 'g:i A') : 'No times set'; ?>
                        </td>
                        <td>
                            <?= $times['end'] ? date_format(date_create($times['end']), 'g:i A') : 'No times set'; ?>
                        </td>
                    </tr>
                <?php endif; ?>
            <?php endforeach; ?>
                </tbody>

            </table>
        </div>

        <div class="schedule-edit">
            <h2>Edit Schedule</h2>
            <form method="post" action="scheduler.php">
                <table>
                    <thead>
                        <tr>
                            <th>Day</th>
                            <th>Start Time</th>
                            <th>End Time</th>
                            <th>Availability</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($schedule as $day => $times):
                            $start = date_create_from_format('H:i:s', $times['start']);
                            $end = date_create_from_format('H:i:s', $times['end']);
                            ?>
                            <tr>
                                <td>
                                    <?= htmlspecialchars($day); ?>
                                </td>
                                <td>
                                    <input type="text" class="timepicker start-time" name="<?= $day; ?>_start_time"
                                        value="<?= date_format($start, 'g:i A'); ?>">
                                </td>
                                <td>
                                    <input type="text" class="timepicker end-time" name="<?= $day; ?>_end_time"
                                        value="<?= date_format($end, 'g:i A'); ?>">
                                </td>
                                <td class = "availability-column">
                                    <input type="checkbox" class="availability-checkbox" name="<?= $day; ?>_availability"
                                        <?= $times['availability'] ? 'checked' : ''; ?>>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <button type="submit">Update Schedule</button>
            </form>
        </div>
    </main>
    <script>
        $(document).ready(function () {
            $('.timepicker').timepicker({
                timeFormat: 'h:mm p',
                interval: 15,
                minTime: '12:00am',
                maxTime: '11:59pm',
                startTime: '12:00am',
                dynamic: false,
                dropdown: true,
                scrollbar: true
            })

            // Set the default end time to 5:00 PM
            $('.timepicker[name$="_end_time"]').timepicker('option', 'defaultTime', '5:00pm');

            $('.availability-checkbox').each(function () {
                // If the checkbox is not checked, disable the time inputs
                if (!$(this).prop('checked')) {
                    $(this).closest('tr').find('.timepicker').prop('disabled', true);
                }
            });

            // When a checkbox is clicked, enable or disable the time inputs based on the checkbox status
            $('.availability-checkbox').on('click', function () {
                $(this).closest('tr').find('.timepicker').prop('disabled', !$(this).prop('checked'));
            });
            
        });
    </script>
</body>

</html>