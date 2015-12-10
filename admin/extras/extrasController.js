(function () {
    angular.module('bkExtras')
        .controller('ExtrasController', ExtrasController);

    ExtrasController.$inject = [];
    function ExtrasController() {
        var vm = this;

        vm.extras = [];
        vm.categories = [{title: 'default', id: 1}, {title: 'Autos', id: 2}];
        vm.fieldTypes = [{id: 1, title: 'Sitzordnung'}, {id: 2, title: 'Bewirtung'}];
        vm.sorting = {col: 'title', asc: true};
        vm.allSelected = false;
        vm.selectedBulkAction = '-1';

        vm.newExtra = {
            show: false,
            title: '',
            category: vm.categories[0],
            type: vm.fieldTypes[0]
        };

        vm.add = addExtra;
        vm.delete = deleteExtra;
        vm.setTitle = setExtraTitle;
        vm.setType = setExtraType;
        vm.setCategory = setExtraCategory;
        vm.setSorting = setSorting;
        vm.selectAll = selectAll;
        vm.getSortingClass = getSortingClass;
        vm.bulkAction = performBulkAction;

        ////////////

        vm.add('Test', vm.fieldTypes[0], vm.categories[0]);
        vm.add('Test2', vm.fieldTypes[0], vm.categories[1]);
        vm.add('Test3', vm.fieldTypes[1], vm.categories[0]);
        vm.add('Test4', vm.fieldTypes[1], vm.categories[1]);

        ////////////

        function addExtra(name, type, category) {
            vm.extras.push({
                title: name,
                type: type,
                category: category,
                selected: false,
                modified: {
                    title: name,
                    type: type,
                    category: category
                },
                showEdit: {
                    title: false,
                    type: false,
                    category: false
                }
            });

            vm.newExtra.show = false;
            vm.newExtra.title = '';

            sortList();
        }

        function deleteExtra(extra) {
            _.remove(vm.extras, extra);
        }

        function sortList() {
            vm.extras = _.sortByOrder(vm.extras, [vm.sorting.col], [vm.sorting.asc ? 'asc' : 'desc']);
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

        function getSortingClass(col) {
            if (vm.sorting.col == col) {
                return vm.sorting.asc ? 'asc' : 'desc';
            }
            return 'desc';
        }

        function setExtraTitle(extra, newTitle) {
            extra.title = newTitle;
            extra.modified.title = newTitle;
            extra.showEdit.title = false;

            sortList();
        }

        function setExtraType(extra, newType) {
            extra.type = newType;
            extra.modified.type = newType;
            extra.showEdit.type = false;

            sortList();
        }

        function setExtraCategory(extra, newCategory) {
            extra.category = newCategory;
            extra.modified.category = newCategory;
            extra.showEdit.category = false;

            sortList();
        }

        function selectAll(select) {
            _.forEach(vm.extras, function (extra) {
                extra.selected = select;
            });
        }

        function performBulkAction(action) {
            if (action === 'delete') {
                _.remove(vm.extras, {selected: true});
            }
        }
    }
})();