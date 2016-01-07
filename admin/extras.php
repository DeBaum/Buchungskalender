<?php
function src($rel_path) {
	echo plugins_url() . '/Buchungskalender/admin/' . $rel_path;
}

function npmSrc($module_rel_path) {
	echo plugins_url() . '/Buchungskalender/node_modules/' . $module_rel_path;
}
?>

<script src="<?php npmSrc('angular/angular.min.js') ?>"></script>
<script src="//localhost.de/static/lodash.min.js"></script>

<script src="<?php src('shared/adminApp.js') ?>"></script>
<script src="<?php src('extras/app.js') ?>"></script>
<script src="<?php src('extras/extrasController.js') ?>"></script>

<style>
	h1 input,
	h1 select {
		vertical-align: middle;
	}

	/* dashicons in Edit-Columns */
	tbody input ~ a > .dashicons,
	tbody select ~ a > .dashicons {
		font-size: 28px;
	}
</style>

<div class="wrap" ng-app="bkExtras" ng-controller="ExtrasController as ex" ng-cloak>
	<h1>
		Buchungskalender › Extras

		<a href="" class="page-title-action" ng-hide="ex.newExtra.show" ng-click="ex.newExtra.show = true">Neu
			hinzufügen
		</a>

		<input type="text" placeholder="Name" ng-show="ex.newExtra.show" ng-model="ex.newExtra.title">
		<select ng-show="ex.newExtra.show" ng-model="ex.newExtra.category"
		        ng-options="cat as cat.title for cat in ex.categories track by cat.id"
		        title="Kategorie, in der das neue Extra verfügbar sein soll"></select>
		<select ng-show="ex.newExtra.show" ng-model="ex.newExtra.type" title="Typ des neuen Extras"
		        ng-options="type as type.title for type in ex.fieldTypes track by type.id"></select>
		<a href="" class="page-title-action" ng-show="ex.newExtra.show"
		   ng-click="ex.add(ex.newExtra.title, ex.newExtra.type, ex.newExtra.category)">Hinzufügen</a>
	</h1>

	<div>
		<div class="tablenav top">
			<div class="alignleft actions bulkactions">
				<select ng-model="ex.selectedBulkAction" title="Mehrfachauswahl">
					<option value="-1">Aktion wählen</option>
					<option value="delete">Löschen</option>
				</select>
				<input type="submit" class="button action" value="Übernehmen"
				       ng-click="ex.bulkAction(ex.selectedBulkAction)">
			</div>
		</div>
		<table class="widefat fixed striped">
			<thead>
			<tr>
				<td class="manage-column column-cb check-column">
					<input type="checkbox" ng-model="ex.allSelected" title="Alle auswählen"
					       ng-change="ex.selectAll(ex.allSelected)">
				</td>
				<th class="manage-column column-primary sortable" ng-class="ex.getSortingClass('title')">
					<a href="" ng-click="ex.setSorting('title')">
						<span>Name</span>
						<span class="sorting-indicator"></span>
					</a>
				</th>
				<th class="manage-column column-primary sortable" ng-class="ex.getSortingClass('type.title')">
					<a href="" ng-click="ex.setSorting('type.title')">
						<span>Art</span>
						<span class="sorting-indicator"></span>
					</a>
				</th>
				<th class="manage-column column-primary sortable" ng-class="ex.getSortingClass('category.title')">
					<a href="" ng-click="ex.setSorting('category.title')">
						<span>Kategorie</span>
						<span class="sorting-indicator"></span>
					</a>
				</th>
			</tr>
			</thead>
			<tfoot>
			<tr>
				<td class="manage-column column-cb check-column">
					<input type="checkbox" ng-model="ex.allSelected" title="Alle auswählen"
					       ng-change="ex.selectAll(ex.allSelected)">
				</td>
				<th class="manage-column column-primary sortable" ng-class="ex.getSortingClass('title')">
					<a href="" ng-click="ex.setSorting('title')">
						<span>Name</span>
						<span class="sorting-indicator"></span>
					</a>
				</th>
				<th class="manage-column column-primary sortable" ng-class="ex.getSortingClass('type.title')">
					<a href="" ng-click="ex.setSorting('type.title')">
						<span>Art</span>
						<span class="sorting-indicator"></span>
					</a>
				</th>
				<th class="manage-column column-primary sortable" ng-class="ex.getSortingClass('category.title')">
					<a href="" ng-click="ex.setSorting('category.title')">
						<span>Kategorie</span>
						<span class="sorting-indicator"></span>
					</a>
				</th>
			</tr>
			</tfoot>
			<tbody>
			<tr ng-repeat="e in ex.extras">
				<th class="check-column">
					<input type="checkbox" ng-model="e.selected" title="{{e.title}} auswählen">
				</th>
				<td>
					<span ng-hide="e.showEdit.title">{{e.title}}</span>

					<span ng-show="e.showEdit.title">
						<input type="text" ng-model="e.modified.title" title="Name des Estras" placeholder="Name">

						<a href="" ng-click="ex.setTitle(e, e.modified.title)">
							<span class="dashicons dashicons-yes"></span>
						</a>
						<a href="" ng-click="e.showEdit.title = false; e.modified.title = e.title;">
							<span class="dashicons dashicons-no"></span>
						</a>
					</span>

					<div class="row-actions" ng-hide="e.showEdit.title">
						<span class="edit">
							<a href="" ng-click="e.showEdit.title = true">Bearbeiten</a>
						</span>
						|
						<span class="delete">
							<a href="" ng-click="ex.delete(e)">
								Löschen
							</a>
						</span>
					</div>
				</td>
				<td>
					<span ng-hide="e.showEdit.type">{{e.type.title}}</span>

					<span ng-show="e.showEdit.type">
						<select ng-options="type as type.title for type in ex.fieldTypes track by type.id"
						        ng-model="e.modified.type" title="Typ des Extras"></select>
						<a href="" ng-click="ex.setType(e, e.modified.type)">
							<span class="dashicons dashicons-yes"></span>
						</a>
						<a href="" ng-click="e.showEdit.type = false; e.modified.type = e.type">
							<span class="dashicons dashicons-no"></span>
						</a>
					</span>

					<div class="row-actions" ng-hide="e.showEdit.type">
						<span class="edit">
							<a href="" ng-click="e.showEdit.type = true">Ändern</a>
						</span>
					</div>
				</td>
				<td>
					<span ng-hide="e.showEdit.category">{{e.category.title}}</span>

					<span ng-show="e.showEdit.category">
						<select ng-options="cat as cat.title for cat in ex.categories track by cat.id"
						        ng-model="e.modified.category" title="Kategorie, in der das Extra verfügbar ist"></select>
						<a href="" ng-click="ex.setCategory(e, e.modified.category)">
							<span class="dashicons dashicons-yes"></span>
						</a>
						<a href="" ng-click="e.showEdit.category = false; e.modified.category = e.category">
							<span class="dashicons dashicons-no"></span>
						</a>
					</span>

					<div class="row-actions" ng-hide="e.showEdit.category">
						<span>
							<a href="" ng-click="e.showEdit.category = true">Ändern</a>
						</span>
					</div>
				</td>
			</tr>
			</tbody>
		</table>
		<div class="tablenav bottom">
			<div class="alignleft actions bulkactions">
				<select ng-model="ex.selectedBulkAction" title="Mehrfachauswahl">
					<option value="-1">Aktion wählen</option>
					<option value="delete">Löschen</option>
				</select>
				<input type="submit" class="button action" value="Übernehmen"
				       ng-click="ex.bulkAction(ex.selectedBulkAction)">
			</div>
			<div class="tablenav-pages one-page">
				<span
					class="displaying-num">{{ex.extras.length}} {{ex.extras.length == 1 ? 'Eintrag' : 'Einträge'}}</span>
			</div>
		</div>
	</div>
</div>