const ctx = document.getElementById('studentChart');

if (ctx) {

    new Chart(ctx, {

        type: 'bar',

        data: {

            labels: ['Khối 1', 'Khối 2', 'Khối 3', 'Khối 4', 'Khối 5'],

            datasets: [{

                label: 'Số học sinh',

                data: [120, 105, 98, 110, 87],

                backgroundColor: [

                    '#2563EB',

                    '#3B82F6',

                    '#60A5FA',

                    '#93C5FD',

                    '#BFDBFE'

                ],

                borderRadius: 10

            }]

        },

        options: {

            responsive: true,

            plugins: {

                legend: {

                    display: false

                }

            },

            scales: {

                y: {

                    beginAtZero: true

                }

            }

        }

    });

}