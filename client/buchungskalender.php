<?php
function src($rel_path) {
	echo plugins_url() . '/Buchungskalender/client/' . $rel_path;
}

function getApiRoot() {
	echo plugins_url() . '/Buchungskalender/api';
}

function npmSrc($module_rel_path) {
	echo plugins_url() . '/Buchungskalender/node_modules/' . $module_rel_path;
}

?>

<script>
	var bkRootPath = '<?php src('') ?>';
	var bkApiRoot = '<?php getApiRoot() ?>';
</script>

<script src="<?php npmSrc('lodash/dist/lodash.min.js') ?>"></script>
<script src="<?php npmSrc('angular/angular.min.js') ?>"></script>
<script src="<?php npmSrc('angular-animate/angular-animate.min.js') ?>"></script>

<script>
	$ = $ || jQuery;
	$.cookie = $.cookie || _.noop;
</script>

<script src="<?php npmSrc('moment/min/moment-with-locales.min.js') ?>"></script>
<script src="<?php npmSrc('moment-range/dist/moment-range.min.js') ?>"></script>
<script src="<?php npmSrc('fullcalendar/dist/fullcalendar.min.js') ?>"></script>
<script src="<?php npmSrc('angular-ui-calendar/src/calendar.js') ?>"></script>
<script src="<?php npmSrc('angular-ui-router/release/angular-ui-router.min.js') ?>"></script>
<script src="<?php npmSrc('angular-resource/angular-resource.min.js') ?>"></script>

<script src="<?php src('resources/app.js') ?>"></script>
<script src="<?php src('resources/categoryResource.js') ?>"></script>
<script src="<?php src('resources/objectResource.js') ?>"></script>
<script src="<?php src('resources/reservationResource.js') ?>"></script>

<script src="<?php src('app.js') ?>"></script>
<script src="<?php src('routes.js') ?>"></script>

<script src="<?php src('categoryService.js') ?>"></script>
<script src="<?php src('objectService.js') ?>"></script>
<script src="<?php src('reservationService.js') ?>"></script>

<script src="<?php src('category-selection/categoryController.js') ?>"></script>
<script src="<?php src('find-date/bookingDataFactory.js') ?>"></script>
<script src="<?php src('find-date/findDateController.js') ?>"></script>
<script src="<?php src('find-date/calendar/calendarController.js') ?>"></script>
<script src="<?php src('find-date/filter/filterController.js') ?>"></script>
<script src="<?php src('book-object/bookObjectController.js') ?>"></script>
<script src="<?php src('book-object/extras/extrasController.js') ?>"></script>

<link rel="stylesheet" href="<?php npmSrc('fullcalendar/dist/fullcalendar.min.css') ?>">
<link rel="stylesheet" href="<?php src('find-date/calendar/ui-calendar-mod.css') ?>">
<link rel="stylesheet" href="<?php src('find-date/find-date.css') ?>">
<link rel="stylesheet" href="<?php src('book-object/book-object.css') ?>">
<link rel="stylesheet" href="<?php src('find-date/filter/filterView.css') ?>">

<div ng-app="bkClient">
	<ui-view>
		<h2>Lade ...</h2>
	</ui-view>
</div>
