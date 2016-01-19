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
                "default": 2,//0 = keine auswahl
                "values": [
                    {"id": 0, "title": "Keine"},
                    {"id": 1, "title": "U-Form"},
                    {"id": 2, "title": "T-Form"}
                ]
            },
            {
                "id": 7,
                "title": "Bewirtung",
                "type": "form",
                "default": true,
                "subfields": [
                    {"type": "text", "title": "Fachbereich", "name": "fb", "default": "FB7"},
                    {"type": "text", "title": "Buget", "name": "buget"},
                    {"type": "text", "title": "Konto", "name": "konto"},
                    {"type": "text", "title": "Kostenstelle", "name": "kostenstelle"},
                    {"type": "textarea", "title": "Speisen/Getränke", "name": "speisen"},
                    {"type": "text", "title": "Personen", "name": "persons"}
                ]
            },
            {
                "id": 12,
                "title": "Anzeige Infotafel",
                "type": "check"
            },
            {
                "id": 14,
                "title": "Externer Internetzugang",
                "type": "check"
            },
            {
                "id": 13,
                "title": "Eventzeit",
                "type": "form",
                "subfields": [
                    {"type": "text", "title": "Start", "name": "time_start"},
                    {"type": "text", "title": "Ende", "name": "Time_stop"}
                ]
            },
            {
                "id": 18,
                "title": "Beschreibung",
                "type": "textarea"
            }
        ];

        vm.selectedExtras = {};
        vm.getFields = getFields;
        vm.getForms = getForms;
        vm.getSelectDefault = getSelectDefault;
        vm.getAll = getAll;

        ////////////

        function getFields() {
            return _.filter(list, function (e) {
                return e.type != 'form';
            });
        }

        function getForms() {
            return _.filter(list, function (e) {
                return e.type == 'form';
            });
        }

        function getSelectDefault(extra) {
            if (extra.type == 'select') {
                return _.first(_.find(extra.values, {"default": true}));
            }
            return null;
        }

        function getAll() {
            return list;
        }
    }
})();