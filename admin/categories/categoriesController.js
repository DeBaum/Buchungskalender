(function () {
    angular.module('bkCategories')
        .controller('CategoriesController', CategoriesController);

    CategoriesController.$inject = [];
    function CategoriesController() {
        var vm = this;
        vm.showNewnameInput = false;
        vm.newCategoryName = '';
        vm.categories = [{title:'default', selected:false, objects: 0}];
        vm.sorting = {col: 'title', asc: true};
        vm.selectedBulkAction = '-1';
        vm.allSelected = false;

        vm.getSortingClass = getSortingClass;
        vm.setSorting = setSorting;
        vm.add = addCategory;
        vm.delete = removeCategory;
        vm.bulkAction = performBulkAction;
        vm.selectAll = selectAll;

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

        function sortList() {
            vm.categories = _.sortByOrder(vm.categories, [vm.sorting.col], [vm.sorting.asc ? 'asc' : 'desc']);
        }

        function addCategory(name) {
            vm.categories.push({
                title: name,
                selected: false,
                objects: 0
            });
            vm.newCategoryName = '';
            vm.showNewnameInput = false;
            sortList();
        }

        function removeCategory(category) {
            _.remove(vm.categories, category);
        }

        function performBulkAction(type) {
            if (type === 'delete') {
                vm.categories = _.filter(vm.categories, {selected: false});
            }
        }

        function selectAll(select) {
            _.forEach(vm.categories, function (item) {
                item.selected = select;
            });
        }
    }
})();