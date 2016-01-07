(function () {
    angular.module('bkClient')
        .controller('BookObjectController', BookObjectController);

    BookObjectController.$inject = ['$scope', 'bookingDataFactory', 'ObjectService', '$state'];
    function BookObjectController($scope, bookingDataFactory, ObjectService, $state) {
        bookingDataFactory.update();

        $scope.bookingData = bookingDataFactory;

        $scope.time = {
            start: bookingDataFactory.start,
            end: bookingDataFactory.end
        };

        $scope.$watch('event.before', setEventTimeStr);
        $scope.$watch('event.after', setEventTimeStr);

        $scope.objects = ObjectService.getForCategory(bookingDataFactory.categoryId);
        $scope.object = _.find($scope.objects, {id: bookingDataFactory.objectId});

        $scope.isEditing = isEditing;

        ////////////

        function isEditing() {
            return $state.is('edit-reservation');
        }

        function setEventTimeStr() {
            if ($scope.event) {
                if ($scope.time.start) {
                    $scope.event.beforeStr = $scope.time.start.clone().add($scope.event.before, 'm').format('H:mm');
                }
                if ($scope.time.end) {
                    $scope.event.afterStr = $scope.time.end.clone().subtract($scope.event.after, 'm').format('H:mm');
                }
            }
        }
    }
})();