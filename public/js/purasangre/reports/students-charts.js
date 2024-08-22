// Function to create a striped pattern
function createStripedPattern(ctx, color1, color2, thickness) {
  const patternCanvas = document.createElement('canvas');
  const size = thickness * 4;
  patternCanvas.width = size;
  patternCanvas.height = size;
  const patternContext = patternCanvas.getContext('2d');

  patternContext.fillStyle = color1;
  patternContext.fillRect(0, 0, size, size);

  patternContext.strokeStyle = color2;
  patternContext.lineWidth = thickness; // Increase this value to make stripes thicker

  // Draw diagonal stripes from bottom-left to top-right
  patternContext.beginPath();
  patternContext.moveTo(0, size);
  patternContext.lineTo(size, 0);
  patternContext.stroke();

  patternContext.beginPath();
  patternContext.moveTo(-size / 2, size / 2);
  patternContext.lineTo(size / 2, -size / 2);
  patternContext.stroke();

  patternContext.beginPath();
  patternContext.moveTo(size / 2, size * 1.5);
  patternContext.lineTo(size * 1.5, size / 2);
  patternContext.stroke();

  return ctx.createPattern(patternCanvas, 'repeat');
}

const ctx = document.getElementById('comparison-bar-chart').getContext('2d');

// Create a striped pattern
const stripedPattern = createStripedPattern(ctx, '#ece0ef', '#757779', 4);

const comparisonBarChart = new Chart(ctx, {
  type: 'bar',
  data: {
    labels: [
      'Ene',
      'Feb',
      'Mar',
      'Abr',
      'May',
      'Jun',
      'Jul',
      'Ago',
      'Sep',
      'Oct',
      'Nov',
      'Dic'
    ],
    datasets: [
      {
        label: 'Ańo Actual',
        data: [200, 300, 400, 500, 400, 300, 200, 300, 400, 500, 600, 700],
        backgroundColor: '#34495F'
      },
      {
        label: 'Ańo Anterior',
        data: [100, 200, 300, 400, 300, 200, 100, 200, 300, 400, 500, 600],
        backgroundColor: stripedPattern
      }
    ]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
      legend: {
        position: 'top'
      }
    },
    scales: {
      y: {
        beginAtZero: true,
        // max: 800,
        grid: {
          display: false
        }
      },
      x: {
        grid: {
          display: false
        }
      }
    },
    elements: {
      bar: {
        borderRadius: 3
      }
    }
    // we use BorderRadius option to add rounded corners to the chart
  }
});

// Log to ensure the chart is created
console.log('Chart created:', comparisonBarChart);
