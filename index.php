<html>

<head>
    <link href="https://cdn.jsdelivr.net/npm/suggestions-jquery@17.10.0/dist/css/suggestions.min.css" type="text/css" rel="stylesheet" />
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/suggestions-jquery@17.10.0/dist/js/jquery.suggestions.min.js"></script>

    <script src="//api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>

    <script type="text/javascript">
        var myMap, multiRoute;
        var points = [
//            [55.749, 37.524],
            "Москва, ул Электрозаводская, 21",
//            "Москва, Успенский пер. 7",
//            "г. Москва, ул. Полбина, 16"
        ];

        $(document).ready(function () {
            $("#address").suggestions({
                token: "1c4367046dae788691e6902907844f258234f338",
                type: "ADDRESS",
                count: 10,
                hint: false,
                constraints: {
                    // ограничиваем поиск Москвой
                    label: "",
                    locations: { region: "Москва" },
//                    deletable: true
                },
                onSelect: function(suggestion) {
                    console.log(suggestion.value);
                    points[1] = suggestion.value;
                    multiRoute = new ymaps.multiRouter.MultiRoute({
                        referencePoints: points,
                        params: {
                            routingMode: 'masstransit'
                        }
                    }, {
                        // Автоматически устанавливать границы карты так, чтобы маршрут был виден целиком.
                        boundsAutoApply: true
                    });
                    myMap.geoObjects.removeAll();
                    myMap.geoObjects.add(multiRoute);
                }
            });

            function init () {

                multiRoute = new ymaps.multiRouter.MultiRoute({
                    referencePoints: points,
                    params: {
                        routingMode: 'masstransit'
                    }
                }, {
                    // Автоматически устанавливать границы карты так, чтобы маршрут был виден целиком.
                    boundsAutoApply: true
                });

                // Создаем кнопку.
                var changeLayoutButton = new ymaps.control.Button({
                    data: { content: "Показывать время для пеших сегментов"},
                    options: { selectOnClick: true }
                });

                // Объявляем обработчики для кнопки.
                changeLayoutButton.events.add('select', function () {
                    multiRoute.options.set(
                        // routeMarkerIconContentLayout - чтобы показывать время для всех сегментов.
                        "routeMarkerIconContentLayout",
                        ymaps.templateLayoutFactory.createClass('{{ properties.duration.text }}')
                    );
                });

                changeLayoutButton.events.add('deselect', function () {
                    multiRoute.options.unset("routeWalkMarkerIconContentLayout");
                });

                // Создаем карту с добавленной на нее кнопкой.
                myMap = new ymaps.Map('map', {
                    center: [55.739625, 37.54120],
                    zoom: 12,
                    controls: [changeLayoutButton]
                }, {
                    buttonMaxWidth: 300
                });

                // Добавляем мультимаршрут на карту.
                myMap.geoObjects.add(multiRoute);
            }

            ymaps.ready(init);
        });
    </script>

    <style>
        #map {
            width: 50%; height: 50%; padding: 0; margin: 0;
        }
    </style>
</head>

<body>
<div id="map"></div>
<input type="text" id="address" style="width: 50%;">
</body>

</html>
