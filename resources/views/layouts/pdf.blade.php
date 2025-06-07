<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Cetak PDF Chart</title>

  <!-- Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <!-- html2canvas & jsPDF -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
</head>
<body>

  <h2>Laporan Penjualan</h2>
  <div id="chart-container" style="width:600px;">
    <canvas id="myChart"></canvas>
  </div>

  <button onclick="downloadPDF()">Download PDF</button>

  <script>
    // Data dari PHP (disimulasikan di sini)
    const labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May'];
    const data = [120, 150, 180, 90, 200];

    // Buat chart
    const ctx = document.getElementById('myChart').getContext('2d');
    const chart = new Chart(ctx, {
      type: 'bar',
      data: {
        labels: labels,
        datasets: [{
          label: 'Penjualan',
          data: data,
          backgroundColor: 'rgba(75, 192, 192, 0.5)',
          borderColor: 'rgba(75, 192, 192, 1)',
          borderWidth: 1
        }]
      },
      options: {
        responsive: true,
        scales: {
          y: { beginAtZero: true }
        }
      }
    });

    // Fungsi Download PDF
    async function downloadPDF() {
      const { jsPDF } = window.jspdf;
      const chartContainer = document.getElementById('chart-container');

      // Ambil tampilan chart jadi gambar
      const canvas = await html2canvas(chartContainer, { scale: 2 });

      const imgData = canvas.toDataURL('image/png');
      const pdf = new jsPDF();

      const pageWidth = pdf.internal.pageSize.getWidth();
      const imgProps = pdf.getImageProperties(imgData);
      const imgWidth = pageWidth;
      const imgHeight = (imgProps.height * imgWidth) / imgProps.width;

      pdf.addImage(imgData, 'PNG', 10, 10, imgWidth - 20, imgHeight);
      pdf.save('laporan-penjualan.pdf');
    }
  </script>

</body>
</html>
