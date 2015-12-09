<?php
function src($rel_path) {
	echo plugins_url() . '/Buchungskalender/admin/' . $rel_path;
}

?>

<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.5/angular.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lodash.js/3.10.1/lodash.min.js"></script>

<div class="wrap" ng-app="bkCategories" ng-controller="CategoriesController as cat" ng-cloak>
	<form ng-submit="cat.add(cat.newCategoryName)">
		<h1>
			Buchungskalender › Kategorien
			<a href="" class="page-title-action" ng-hide="cat.showNewnameInput" ng-click="cat.showNewnameInput = true;">
				Neu hinzufügen
			</a>
			<input type="text" style="vertical-align: text-bottom;" ng-model="cat.newCategoryName"
			       ng-show="cat.showNewnameInput" title="Name der neuen Kategorie" placeholder="Name">
			<a href="" class="page-title-action" ng-click="cat.add(cat.newCategoryName)" ng-show="cat.showNewnameInput">
				Hinzufügen
			</a>
		</h1>
	</form>

	<script src="<?php src('shared/adminApp.js') ?>"></script>
	<script src="<?php src('categories/app.js') ?>"></script>
	<script src="<?php src('categories/categoriesController.js') ?>"></script>

	<div>
		<div class="tablenav top">
			<div class="alignleft actions bulkactions">
				<select name="bulkAction1" ng-model="cat.selectedBulkAction" title="Mehrfachauswahl">
					<option value="-1">Aktion wählen</option>
					<option value="delete">Löschen</option>
				</select>
				<input type="submit" class="button action" value="Übernehmen"
				       ng-click="cat.bulkAction(cat.selectedBulkAction)">
			</div>
		</div>
		<table class="widefat fixed striped">
			<thead>
			<tr>
				<td class="manage-column column-cb check-column">
					<input type="checkbox" ng-model="cat.allSelected" ng-change="cat.selectAll(cat.allSelected)"
					       title="Alle auswählen">
				</td>
				<th class="manage-column column-columnname manage-column column-primary sortable"
				    ng-class="cat.getSortingClass('title')">
					<a ng-click="cat.setSorting('title')">
						<span>Name</span>
						<span class="sorting-indicator"></span>
					</a>
				</th>
				<th class="manage-column column-columnname num sortable" ng-class="cat.getSortingClass('objects')">
					<a ng-click="cat.setSorting('objects')">
						<span># Objekte</span>
						<span class="sorting-indicator"></span>
					</a>
				</th>
			</tr>
			</thead>
			<tfoot>
			<tr>
				<td class="manage-column column-cb check-column">
					<input type="checkbox" ng-model="cat.allSelected" ng-change="cat.selectAll(cat.allSelected)"
					       title="Alle auswählen">
				</td>
				<th class="manage-column column-columnname manage-column column-primary sortable"
				    ng-class="cat.getSortingClass('title')">
					<a ng-click="cat.setSorting('title')">
						<span>Name</span>
						<span class="sorting-indicator"></span>
					</a>
				</th>
				<th class="manage-column column-columnname num sortable" ng-class="cat.getSortingClass('objects')">
					<a ng-click="cat.setSorting('objects')">
						<span># Objekte</span>
						<span class="sorting-indicator"></span>
					</a>
				</th>
			</tr>
			</tfoot>

			<tbody>
			<tr ng-repeat="c in cat.categories">
				<th class="check-column">
					<input type="checkbox" ng-model="c.selected" title="{{c.title}} auswählen">
				</th>
				<td>
					<span ng-hide="c.showTitleEdit">{{c.title}}</span>

					<span ng-show="c.showTitleEdit">
						<input type="text" ng-model="c.modifiedTitle">
						<a href="" ng-click="cat.setTitle(c, c.modifiedTitle)">
							<span class="dashicons dashicons-yes"></span>
						</a>
						<a href="" ng-click="c.modifiedTitle = c.title; c.showTitleEdit = false">
							<span class="dashicons dashicons-no"></span>
						</a>
					</span>

					<div class="row-actions" ng-hide="c.showTitleEdit">
						<span class="edit">
							<a href="" ng-click="c.showTitleEdit = true">Bearbeiten</a>
						</span>
						|
						<span class="delete">
							<a href="" ng-click="cat.delete(c)">Löschen</a>
						</span>
					</div>
				</td>
				<td>
					{{c.objects}}
				</td>
			</tr>
			</tbody>
		</table>
		<div class="tablenav bottom">
			<div class="alignleft actions bulkactions">
				<select name="bulkAction2" ng-model="cat.selectedBulkAction" title="Mehrfachauswahl">
					<option value="-1">Aktion wählen</option>
					<option value="delete">Löschen</option>
				</select>
				<input type="submit" class="button action" value="Übernehmen"
				       ng-click="cat.bulkAction(cat.selectedBulkAction)">
			</div>

			<div class="tablenav-pages one-page">
				<span class="displaying-num">{{cat.categories.length}} {{cat.categories.length == 1 ? 'Eintrag' : 'Einträge'}}</span>
			</div>
		</div>
	</div>
</div>