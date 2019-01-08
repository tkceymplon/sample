<template>
    <card class="flex flex-col items-center justify-center">
        <div class="px-3 py-3">

            <div id="chart_div"></div>
        </div>
    </card>
</template>

<script>
    let recaptchaScript = document.createElement('script');
    recaptchaScript.setAttribute('src', 'https://www.gstatic.com/charts/loader.js')
    document.head.appendChild(recaptchaScript)
    export default {
        props: [
            'card',
        ],

        data() {
            return {
                tasks: [],
                showCard: false,
            }
        },

        mounted() {
            this.getData();
        },
        methods: {

            async getData() {
                const {data} = await Nova.request().get(
                    `/nova-vendor/Gantt/tasks`
                );
                ;
                var allTasks = data.map(function (value) {
                    var id = value[0].toString();
                    var startDate = new Date(value[2]);
                    var endDate = new Date(value[3]);
                    return [
                        id,
                        value[1],
                        startDate,
                        endDate,
                        null,
                        value[5],
                        value[6].toString(),
                    ];
                });

                this.tasks = allTasks;
                if (this.tasks.length != 0) {
                    this.getTasks();
                    showCard:true;
                }
            },
            getTasks() {
                const drawChart = () => {
                    var data = new google.visualization.DataTable();
                    data.addColumn('string', 'Task ID');
                    data.addColumn('string', 'Task Name');
                    data.addColumn('date', 'Start Date');
                    data.addColumn('date', 'End Date');
                    data.addColumn('number', 'Duration');
                    data.addColumn('number', 'Percent Complete');
                    data.addColumn('string', 'Dependencies');

                    for (var i = 0; i < this.tasks.length; i++) {
                        data.addRows([
                            this.tasks[i],
                        ]);
                    }

                    var options = {
                        height: 400,
                        width: 900,
                        gantt: {
                            criticalPathEnabled: false,
                            arrow: {
                                angle: 100,
                                width: 1,
                                color: 'green',
                                radius: 0
                            }
                        }
                    };

                    var chart = new google.visualization.Gantt(document.getElementById('chart_div'));
                    chart.draw(data, options);
                }
                google.charts.load('current', {'packages': ['gantt']});
                google.charts.setOnLoadCallback(drawChart);

            },
        },


    }
</script>
