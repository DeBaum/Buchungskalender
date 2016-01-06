(function () {
    angular.module('bkClient')
        .controller('CategoryController', CategoryController);

    CategoryController.$inject = ['bookingDataFactory'];
    function CategoryController(bookingDataFactory) {
        var vm = this;
        vm.categories = [
            {id: 1, title: 'Autos', thumbnail: '//upload.wikimedia.org/wikipedia/commons/9/95/Ferrari_P4-5.jpg'},
            {
                id: 2,
                title: 'Räume',
                thumbnail: '//upload.wikimedia.org/wikipedia/commons/d/df/Ravensburg_Mevlana-Moschee_Schulungsraum.jpg'
            },
            {
                id: 3,
                title: 'Beamer',
                thumbnail: '//pixabay.com/static/uploads/photo/2013/10/17/14/15/beamer-196951_960_720.jpg'
            }
        ];

        bookingDataFactory.start = null;
        bookingDataFactory.end = null;
        ////////////
    }
})();