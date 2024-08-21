document.addEventListener('DOMContentLoaded', function() {
  const ctx = document.getElementById('students-chart').getContext('2d');

  // Function to update the chart with data
  function updateChart(year, month) {
    fetch('/reports/students-filter', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': token
      },
      body: JSON.stringify({ year: year, month: month })
    })
      .then(response => response.json())
      .then(data => {
        const labels = data.data.map(item => `${item.year}-${item.month}`);
        const activeStartData = data.data.map(
          item => item.active_students_start
        );
        const activeEndData = data.data.map(item => item.active_students_end);
        const newStudentsData = data.data.map(item => item.new_students);
        const dropoutsData = data.data.map(item => item.dropouts);

        const myChart = new Chart(ctx, {
          type: 'line', // or 'bar', 'pie', etc.
          data: {
            labels: labels,
            datasets: [
              {
                label: 'Activos al inicio',
                data: activeStartData,
                borderColor: 'rgba(75, 192, 192, 1)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                fill: false
              },
              {
                label: 'Activos al término',
                data: activeEndData,
                borderColor: 'rgba(54, 162, 235, 1)',
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                fill: false
              },
              {
                label: 'Nuevos alumnos',
                data: newStudentsData,
                borderColor: 'rgba(255, 206, 86, 1)',
                backgroundColor: 'rgba(255, 206, 86, 0.2)',
                fill: false
              },
              {
                label: 'Bajas',
                data: dropoutsData,
                borderColor: 'rgba(255, 99, 132, 1)',
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                fill: false
              }
            ]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
              x: {
                display: true,
                title: {
                  display: true,
                  text: 'Mes'
                }
              },
              y: {
                display: true,
                title: {
                  display: true,
                  text: 'Cantidad de Alumnos'
                }
              }
            }
          }
        });
      });
  }

  // Initial chart load
  const initialYear = document.getElementById('students-year-select').value;
  const initialMonth = document.getElementById('students-month-select').value;
  updateChart(initialYear, initialMonth);

  // Update chart when filters change
  document
    .getElementById('students-year-select')
    .addEventListener('change', function() {
      const year = this.value;
      const month = document.getElementById('students-month-select').value;
      updateChart(year, month);
    });

  document
    .getElementById('students-month-select')
    .addEventListener('change', function() {
      const month = this.value;
      const year = document.getElementById('students-year-select').value;
      updateChart(year, month);
    });
});
