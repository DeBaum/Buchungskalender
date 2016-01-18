(function () {
    angular.module('bkClient')
        .factory('CategoryService', CategoryService);

    CategoryService.$inject = ['CategoryResource'];
    function CategoryService(Category) {
        var service = {
            getById: getById,
            load: load,
            categories: []
        };

        return service;

        //////////
        function getById(id) {
            return _.find(service.categories, {id: id});
        }

        function load(id) {
            var fn = Category.get;
            if (!id) {
                fn = Category.getAll;
            }
            fn({id: id}, function (categories) {
                if (!_.isArray(categories)) {
                    categories = [categories];
                }
                _.forEach(categories, insertCategory);
            });
        }

        function insertCategory(category) {
            var index = _.findIndex(service.categories, {id: category.id});
            if (index === -1) {
                service.categories.push(category);
            } else {
                service.categories[index] = category;
            }
        }
    }
})();