<?php
function src($rel_path) {
	echo plugins_url() . '/Buchungskalender/client/' . $rel_path;
}

?>

<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.5/angular.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lodash.js/3.10.1/lodash.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.6/moment-with-locales.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.5.0/fullcalendar.min.js"></script>

<script src="<?php src('git-submodules/ui-calendar/src/calendar.js') ?>"></script>

<script src="<?php src('app.js') ?>"></script>
<script src="<?php src('calendar/calendarController.js') ?>"></script>

<!--<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.5.0/fullcalendar.print.css">-->
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.5.0/fullcalendar.min.css">

<link rel="stylesheet" href="<?php src('calendar/ui-calendar-mod.css') ?>">

<div ng-app="bkClient">
	<h1>Buchungskalender</h1>

	<div ng-controller="CalendarController as cal">
		<div ui-calendar="cal.config" ng-model="cal.events"></div>
	</div>
</div>
