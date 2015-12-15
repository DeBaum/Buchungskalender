(function () {
    angular.module('bkClient')
        .config(routes);

    routes.$inject = ['$stateProvider', '$urlRouterProvider'];
    function routes($stateProvider, $urlRouterProvider) {
        $urlRouterProvider.otherwise('/category');

        $stateProvider.state('choose-category', {
            url: '/category',
            templateUrl: bkRootPath + 'category-selection/categoryView.html'
        });

        $stateProvider.state('find-date', {
            url: '/category/:categoryId/date',
            templateUrl: bkRootPath + 'find-date/findDateView.html'
        });

        $stateProvider.state('book-object', {
            url: '/category/:categoryId/object/:objectId/book',
            templateUrl: bkRootPath + 'bookObject/bookObjectView.html'
        });
    }
})();