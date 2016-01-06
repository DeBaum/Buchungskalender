<?php
function src($rel_path) {
	echo plugins_url() . '/Buchungskalender/client/' . $rel_path;
}

function npmSrc($module_rel_path) {
	echo plugins_url() . '/Buchungskalender/node_modules/' . $module_rel_path;
}

?>

<script>
	var bkRootPath = '<?php src('') ?>';
</script>

<script src="//ajax.googleapis.com/ajax/libs/angularjs/1.4.5/angular.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/lodash.js/3.10.1/lodash.min.js"></script>

<script>$ = $ || jQuery</script>
<script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.6/moment-with-locales.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.5.0/fullcalendar.min.js"></script>

<script src="<?php npmSrc('angular-ui-calendar/src/calendar.js') ?>"></script>
<script src="<?php npmSrc('angular-ui-router/release/angular-ui-router.min.js') ?>"></script>

<script src="<?php src('app.js') ?>"></script>
<script src="<?php src('routes.js') ?>"></script>

<script src="<?php src('category-selection/categoryController.js') ?>"></script>
<script src="<?php src('find-date/bookingDataFactory.js') ?>"></script>
<script src="<?php src('find-date/findDateController.js') ?>"></script>
<script src="<?php src('find-date/calendar/calendarController.js') ?>"></script>
<script src="<?php src('find-date/filter/filterController.js') ?>"></script>
<script src="<?php src('book-object/bookObjectController.js') ?>"></script>
<script src="<?php src('book-object/extras/extrasController.js') ?>"></script>

<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.5.0/fullcalendar.min.css">

<link rel="stylesheet" href="<?php src('find-date/calendar/ui-calendar-mod.css') ?>">
<link rel="stylesheet" href="<?php src('find-date/find-date.css') ?>">
<link rel="stylesheet" href="<?php src('book-object/book-object.css') ?>">
<link rel="stylesheet" href="<?php src('find-date/filter/filterView.css') ?>">

<div ng-app="bkClient">
	<h1>Buchungskalender</h1>

	<ui-view>
		<h2>Lade ...</h2>
	</ui-view>
</div>
