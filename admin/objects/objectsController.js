(function () {
    angular.module('bkObjects')
        .controller('ObjectsController', ObjectsController);

    ObjectsController.$inject = [];
    function ObjectsController() {
        var vm = this;
        vm.categories = [{title: 'default', id: 1}, {title: 'Autos', id: 2}];
        vm.objects = [{title: 'Auto', modifiedTitle: 'Auto', selected: false, category: vm.categories[0], modifiedCategory: vm.categories[0], quantity: 1, modifiedQuantity: 1}];
        vm.newObjectName = '';
        vm.newObjectCategory = vm.categories[0];
        vm.showNewNameInput = false;
        vm.sorting = {col: 'title', asc: true};
        vm.allSelected = false;
        vm.selectedBulkAction = '-1';

        vm.add = addObject;
        vm.delete = deleteObject;
        vm.setTitle = setObjectTitle;
        vm.setQuantity = setObjectQuantity;
        vm.setCategory = setCategory;
        vm.setSorting = setSorting;
        vm.getSortingClass = getSortingClass;
        vm.selectAll = selectAll;
        vm.bulkAction = performBulkAction;

        ////////////

        function getSortingClass(col) {
            if (vm.sorting.col == col) {
                return vm.sorting.asc ? 'asc' : 'desc';
            }
            return 'desc';
        }

        function setSorting(name) {
            if (vm.sorting.col == name) {
                vm.sorting.asc = !vm.sorting.asc;
            } else {
                vm.sorting.col = name;
                vm.sorting.asc = true;
            }
            sortList();
        }

        function addObject(name, category) {
            vm.objects.push({
                title: name,
                modifiedTitle: name,
                showTitleEdit: false,
                selected: false,
                category: category,
                modifiedCategory: category,
                showCategoryEdit: false,
                quantity: 1,
                modifiedQuantity: 1,
                showQuantityEdit: false
            });

            vm.showNewNameInput = false;
            vm.newObjectName = '';

            sortList();
        }

        function deleteObject(object) {
            _.remove(vm.objects, object);
        }

        function setObjectTitle(object, newTitle) {
            object.title = newTitle;
            object.modifiedTitle = newTitle;
            object.showTitleEdit = false;

            sortList();
        }

        function setObjectQuantity(object, quantity) {
            object.quantity = quantity;
            object.showQuantityEdit = false;

            sortList();
        }

        function setCategory(object, category) {
            object.category = category;
            object.showCategoryEdit = false;

            sortList();
        }

        function selectAll(select) {
            _.forEach(vm.objects, function (object) {
                object.selected = select;
            });
        }

        function sortList() {
            vm.objects = _.sortByOrder(vm.objects, [vm.sorting.col], [vm.sorting.asc ? 'asc' : 'desc']);
        }

        function performBulkAction(action) {
            if (action === 'delete') {
                _.remove(vm.objects, {selected: true});
            }
        }
    }
})();