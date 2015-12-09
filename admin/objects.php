<?php
function src($rel_path) {
	echo plugins_url() . '/Buchungskalender/admin/' . $rel_path;
}

?>

<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.5/angular.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lodash.js/3.10.1/lodash.min.js"></script>

<script src="<?php src('shared/adminApp.js') ?>"></script>
<script src="<?php src('objects/app.js') ?>"></script>
<script src="<?php src('objects/objectsController.js') ?>"></script>

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

<div class="wrap" ng-app="bkObjects" ng-controller="ObjectsController as obj" ng-cloak>
	<form ng-submit="obj.add(obj.newObjectName)">
		<h1>
			Buchungskalender › Objekte
			<a href="" class="page-title-action" ng-hide="obj.showNewNameInput" ng-click="obj.showNewNameInput = true;">
				Neu hinzufügen
			</a>
			<input type="text" ng-model="obj.newObjectName"
			       ng-show="obj.showNewNameInput" title="Name des neuen Objektes" placeholder="Name">
			<select ng-options="cat as cat.title for cat in obj.categories track by cat.id"
			        ng-show="obj.showNewNameInput" ng-model="obj.newObjectCategory"
			        title="Kategorie, in der das neue Objekt angezeigt wird">
			</select>
			<a href="" class="page-title-action" ng-show="obj.showNewNameInput"
			   ng-click="obj.add(obj.newObjectName, obj.newObjectCategory)">
				Hinzufügen
			</a>
		</h1>
	</form>

	<div>
		<div class="tablenav top">
			<div class="alignleft actions bulkactions">
				<select name="bulkAction1" ng-model="obj.selectedBulkAction" title="Mehrfachauswahl">
					<option value="-1">Aktion wählen</option>
					<option value="delete">Löschen</option>
				</select>
				<input type="submit" class="button action" value="Übernehmen"
				       ng-click="obj.bulkAction(obj.selectedBulkAction)">
			</div>
		</div>
		<table class="widefat fixed striped">
			<thead>
			<tr>
				<td class="manage-column column-cb check-column">
					<input type="checkbox" title="Alle auswählen" ng-model="obj.allSelected"
					       ng-change="obj.selectAll(obj.allSelected)">
				</td>
				<th class="manage-column manage-column column-primary sortable"
				    ng-class="obj.getSortingClass('title')">
					<a href="" ng-click="obj.setSorting('title')">
						<span>Name</span>
						<span class="sorting-indicator"></span>
					</a>
				</th>
				<th class="manage-column manage-column sortable" ng-class="obj.getSortingClass('quantity')">
					<a href="" ng-click="obj.setSorting('quantity')">
						<span>Anzahl</span>
						<span class="sorting-indicator"></span>
					</a>
				</th>
				<th class="manage-column manage-column sortable" ng-class="obj.getSortingClass('category.title')">
					<a href="" ng-click="obj.setSorting('category.title')">
						<span>Kategorie</span>
						<span class="sorting-indicator"></span>
					</a>
				</th>
			</tr>
			</thead>
			<tfoot>
			<tr>
				<td class="manage-column column-cb check-column">
					<input type="checkbox" title="Alle auswählen" ng-model="obj.allSelected"
					       ng-change="obj.selectAll(obj.allSelected)">
				</td>
				<th class="manage-column manage-column column-primary sortable"
				    ng-class="obj.getSortingClass('title')">
					<a href="" ng-click="obj.setSorting('title')">
						<span>Name</span>
						<span class="sorting-indicator"></span>
					</a>
				</th>
				<th class="manage-column manage-column sortable" ng-class="obj.getSortingClass('quantity')">
					<a href="" ng-click="obj.setSorting('quantity')">
						<span>Anzahl</span>
						<span class="sorting-indicator"></span>
					</a>
				</th>
				<th class="manage-column manage-column sortable" ng-class="obj.getSortingClass('category.title')">
					<a href="" ng-click="obj.setSorting('category.title')">
						<span>Kategorie</span>
						<span class="sorting-indicator"></span>
					</a>
				</th>
			</tr>
			</tfoot>

			<tbody>
			<tr ng-repeat="o in obj.objects">
				<th class="check-column">
					<input type="checkbox" ng-model="o.selected" title="{{o.title}} auswählen">
				</th>
				<td>
					<span ng-hide="o.showTitleEdit">{{o.title}}</span>
					
					<span ng-show="o.showTitleEdit">
						<input type="text" title="Name des Objektes" ng-model="o.modifiedTitle">
						<a href="" ng-click="obj.setTitle(o, o.modifiedTitle)">
							<span class="dashicons dashicons-yes"></span>
						</a>
						<a href="" ng-click="o.modifiedTitle = o.title; o.showTitleEdit = false">
							<span class="dashicons dashicons-no"></span>
						</a>
					</span>

					<div class="row-actions" ng-hide="o.showTitleEdit">
						<span class="edit">
							<a href="" ng-click="o.showTitleEdit = true">Bearbeiten</a>
						</span>
						|
						<span class="delete">
							<a href="" ng-click="obj.delete(o)">Löschen</a>
						</span>
					</div>
				</td>
				<td>
					<span ng-hide="o.showQuantityEdit">{{o.quantity}}</span>

					<span ng-show="o.showQuantityEdit">
						<input type="number" min="1" ng-model="o.modifiedQuantity" style="width: 4em"
						       ng-show="o.showQuantityEdit" title="Anzahl dieses Typs"/>
						<a href="" ng-show="o.showQuantityEdit"
						   ng-click="obj.setQuantity(o, o.modifiedQuantity)">
							<span class="dashicons dashicons-yes"></span>
						</a>
						<a href="" ng-show="o.showQuantityEdit"
						   ng-click="o.modifiedQuantity = o.quantity; o.showQuantityEdit = false">
							<span class="dashicons dashicons-no"></span>
						</a>
					</span>

					<div class="row-actions" ng-hide="o.showQuantityEdit">
						<span class="edit">
							<a href="" ng-click="o.showQuantityEdit = true" ng-hide="o.showQuantityEdit">Ändern</a>
						</span>
					</div>
				</td>
				<td>
					<span ng-hide="o.showCategoryEdit">{{o.category.title}}</span>

					<span ng-show="o.showCategoryEdit">
						<select ng-model="o.modifiedCategory" ng-show="o.showCategoryEdit"
						        ng-options="cat as cat.title for cat in obj.categories track by cat.id"
						        title="Kategorie, in der dieses Objekt angezeigt wird"></select>
						<a href="" ng-click="obj.setCategory(o, o.modifiedCategory)" ng-show="o.showCategoryEdit">
							<span class="dashicons dashicons-yes"></span>
						</a>
						<a href="" ng-show="o.showCategoryEdit"
						   ng-click="o.modifiedCategory = o.category; o.showCategoryEdit = false">
							<span class="dashicons dashicons-no"></span>
						</a>
					</span>

					<div class="row-actions" ng-hide="o.showCategoryEdit">
						<span>
							<a href="" ng-click="o.showCategoryEdit = true" ng-hide="o.showCategoryEdit">Ändern</a>
						</span>
					</div>
				</td>
			</tr>
			</tbody>
		</table>
		<div class="tablenav bottom">
			<div class="alignleft actions bulkactions">
				<select name="bulkAction2" ng-model="obj.selectedBulkAction" title="Mehrfachauswahl">
					<option value="-1">Aktion wählen</option>
					<option value="delete">Löschen</option>
				</select>
				<input type="submit" class="button action" value="Übernehmen"
				       ng-click="obj.bulkAction(obj.selectedBulkAction)">
			</div>

			<div class="tablenav-pages one-page">
				<span class="displaying-num">{{obj.objects.length}} {{obj.objects.length == 1 ? 'Eintrag' : 'Einträge'}}</span>
			</div>
		</div>
	</div>
</div>