import Chart from 'chart.js/auto';
import axios from 'axios';

if ($('#waterings').length > 0) axios.get('/water/chart')
  .then(response => {
    const data = response.data;
    const ctx = $('#waterings').get(0).getContext('2d');

    // Empty data
    const empty = {
      id: 'emptyDoughnut',
      afterDraw(chart, args, options) {
        const { datasets } = chart.data;
        const { color, width, radiusDecrease } = options;
        let hasData = false;

        for (let i = 0; i < datasets.length; i += 1) hasData |= datasets[i].data.length > 0;

        if (!hasData) {
          const { chartArea: { left, top, right, bottom }, ctx } = chart;

          ctx.beginPath();
          ctx.lineWidth = width || 2;
          ctx.strokeStyle = color || '#000';
          ctx.arc((left + right) / 2, (top + bottom) / 2, ((Math.min(right - left, bottom - top) / 2) - radiusDecrease || 0), 0, 2 * Math.PI);
          ctx.stroke();
        }
      }
    };

    if (ctx) new Chart(ctx, {
      type: 'doughnut',
      options: {
        responsive: true,
        plugins: {
          legend: {
            position: 'top',
          },
          title: {
            display: true,
            text: 'Watering'
          },
          emptyDoughnut: {
            color: '#36b9cc',
            width: 2,
            radiusDecrease: 20
          }
        }
      },
      plugins: [empty],
      data: {
        labels: data.length > 0 ? data.map(row => row.name) : [],
        datasets: [{
          label: 'Watering',
          data: data.length > 0 ? data.map(row => row.count) : [],
          backgroundColor: data.length > 0 ? data.map(row => row.color) : [],
        }]
      }
    });
  })
  .catch(error => {
    console.error('Error fetching chart data:', error);
  });
