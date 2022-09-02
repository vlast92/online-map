"use strict";

function YandexMap(params) {

    var map = new ymaps.Map(params.id, {
        center: params.centerCoord,
        zoom: params.zoom,
        controls: []
    });

    if (!params.wheel_zoom) map.behaviors.disable('scrollZoom');

    this.addPlace = function (params) {

        for (var i = 0; i < params.length; i++) {

            var icon = params[i].icon ? params[i].icon : {},
                placemark = new ymaps.Placemark([params[i].position.lat, params[i].position.lng], {
                hintContent: params[i].name,
                balloonContent: params[i].infoWindowContent
            }, icon);
            map.geoObjects.add(placemark);
        }
    };

    this.addPlacesFromData = function (params) {
        var objectManager = new ymaps.ObjectManager({
            // Чтобы метки начали кластеризоваться, выставляем опцию.
            clusterize: true,
            // ObjectManager принимает те же опции, что и кластеризатор.
            gridSize: 32,
            clusterDisableClickZoom: true
        });

        // Чтобы задать опции одиночным объектам и кластерам,
        // обратимся к дочерним коллекциям ObjectManager.
        objectManager.objects.options.set('preset', 'islands#greenDotIcon');
        objectManager.clusters.options.set('preset', 'islands#greenClusterIcons');
        map.geoObjects.add(objectManager);

        $.ajax({
            url: params.data_path
        }).done(function (data) {
            objectManager.add(data);
        });
    };

    this.addControl = function (params) {

        switch (params) {
            case "search_control":
                map.controls.add(new ymaps.control.SearchControl({
                    options: {
                        // Пусть элемент управления будет
                        // в виде поисковой строки.
                        size: 'large',
                        // Включим возможность искать
                        // не только топонимы, но и организации.
                        provider: 'yandex#search'
                    }
                }));
                break;
            default:
                map.controls.add(params);
        }
    };

    /** открываем функции и переменные, назначая их свойствам объекта */
    return {
        addControl: this.addControl,
        addPlace: this.addPlace,
        addPlacesFromData: this.addPlacesFromData
        /*clearMarkers: this.clearMarkers*/
    };
}
//# sourceMappingURL=yandex.js.map