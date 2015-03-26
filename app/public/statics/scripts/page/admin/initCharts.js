/**
 * Created by liuhui on 15-2-13.
 */
//chart with points
if ($("#sincos").length) {
    (function (){
        var sin = [], cos = [];

        for (var i = 0; i < 15; i += 0.5) {
            sin.push([i, Math.sin(i) / i]);
            cos.push([i, Math.cos(i)]);
        }
        var pv = fixChart(fakeDataForChart());
        console.log(pv);
        console.log(sin);
        var plot = $.plot($("#sincos"),
            [
                { data: pv, label: "PV"},
            ], {
                series: {
                    lines: { show: true  },
                    points: { show: true }
                },
                grid: { hoverable: true, clickable: true, backgroundColor: { colors: ["#fff", "#eee"] } },
                yaxis: { min: 0, max: fakeDataForChart().aViewMax },
                colors: ["#539F2E", "#3C67A5"]
            });

        function showTooltip(x, y, contents) {
            $('<div id="tooltip">' + contents + '</div>').css({
                position: 'absolute',
                display: 'none',
                top: y + 5,
                left: x + 5,
                border: '1px solid #fdd',
                padding: '2px',
                'background-color': '#dfeffc',
                opacity: 0.80
            }).appendTo("body").fadeIn(200);
        }

        var previousPoint = null;
        $("#sincos").bind("plothover", function (event, pos, item) {
            $("#x").text(pos.x.toFixed(2));
            $("#y").text(pos.y.toFixed(2));

            if (item) {
                if (previousPoint != item.dataIndex) {
                    previousPoint = item.dataIndex;

                    $("#tooltip").remove();
                    var x = item.datapoint[0].toFixed(2),
                        y = item.datapoint[1].toFixed(2);

                    showTooltip(item.pageX, item.pageY,
                        item.series.label + " : " + y);
                }
            }
            else {
                $("#tooltip").remove();
                previousPoint = null;
            }
        });

        //$("#sincos").bind("plotclick", function (event, pos, item) {
        //    if (item) {
        //        $("#clickdata").text("You clicked point " + item.dataIndex + " in " + item.series.label + ".");
        //        plot.highlight(item.series, item.datapoint);
        //    }
        //});

        function fakeDataForChart(){
            return '{"aTotal":328,"aViewPre":[{"id":"102","articles_id":"193","view_count":"55028"},{"id":"106","articles_id":"196","view_count":"44451"},{"id":"111","articles_id":"201","view_count":"43015"},{"id":"104","articles_id":"194","view_count":"40350"},{"id":"112","articles_id":"202","view_count":"38217"},{"id":"101","articles_id":"191","view_count":"31759"},{"id":"79","articles_id":"167","view_count":"29426"},{"id":"113","articles_id":"203","view_count":"29017"},{"id":"123","articles_id":"213","view_count":"26781"},{"id":"280","articles_id":"376","view_count":"25983"},{"id":"121","articles_id":"211","view_count":"25530"},{"id":"279","articles_id":"373","view_count":"23220"},{"id":"107","articles_id":"199","view_count":"23136"},{"id":"156","articles_id":"245","view_count":"22382"},{"id":"283","articles_id":"379","view_count":"22259"},{"id":"301","articles_id":"400","view_count":"21929"},{"id":"274","articles_id":"370","view_count":"21890"},{"id":"95","articles_id":"184","view_count":"21786"},{"id":"120","articles_id":"209","view_count":"21664"},{"id":"109","articles_id":"197","view_count":"21590"},{"id":"151","articles_id":"240","view_count":"21549"},{"id":"253","articles_id":"351","view_count":"21349"},{"id":"143","articles_id":"235","view_count":"21037"},{"id":"294","articles_id":"389","view_count":"20753"},{"id":"128","articles_id":"218","view_count":"20744"},{"id":"133","articles_id":"222","view_count":"20692"},{"id":"168","articles_id":"258","view_count":"20683"},{"id":"103","articles_id":"192","view_count":"20456"},{"id":"230","articles_id":"325","view_count":"20375"},{"id":"125","articles_id":"217","view_count":"20218"}],"aViewMax":55028}';
        }

        function fixChart(data){

            var str = JSON.parse(data);
            console.log(str);

            var chartData = [], view = str.aViewPre;
            for(var i = 0; i < view.length; i += 1){
                var v = view[i]['view_count'];
                chartData.push([i, view[i]['view_count']]);
            }
            return chartData;
        }
        //fixChart(fakeDataForChart());

    })();
}