(function () {
    angular.module('bkClient')
        .controller('ExtrasController', ExtrasController);

    ExtrasController.$inject = [];
    function ExtrasController() {
        var vm = this;
        var list = [
            {
                "id": 1,
                "title": "Sitzordung",
                "type": "select",
                "name": "sitzordung",
                "values": [
                    {"id": 1, "title": "U-Form"},
                    {"id": 2, "title": "T-Form", "default": true}
                ]
            },
            {
                "id": 7,
                "title": "Bewirtung",
                "type": "form",
                "subfields": [
                    {"type": "text", "title": "Fachbereich", "name": "fb", "validate": "js-function oder RegEx"},
                    {"type": "text", "title": "Buget", "name": "buget"}
                ]
            },
            {
                "id": 12,
                "title": "Anzeige Infotafel",
                "type": "check",
                "name": "infotafel"
            }
        ];

        vm.selectedExtras = {};
        vm.getFields = getFields;
        vm.getForms = getForms;
        vm.getSelectDefault = getSelectDefault;

        ////////////

        function getFields() {
            return _.filter(list, function(e) {
                return e.type != 'form';
            });
        }

        function getForms() {
            return _.filter(list, function(e) {
                return e.type == 'form';
            });
        }

        function getSelectDefault(extra) {
            if (extra.type == 'select') {
                return _.first(_.find(extra.values, {"default": true}));
            }
            return null;
        }
    }
})();