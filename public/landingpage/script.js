 // ===== HEADER SLIDER SMOOTH SIDEWAYS =====
      function pad2(n) {
        return n < 10 ? "0" + n : n;
      }
      function getDateString() {
        const now = new Date();
        const bulan = [
          "Januari",
          "Februari",
          "Maret",
          "April",
          "Mei",
          "Juni",
          "Juli",
          "Agustus",
          "September",
          "Oktober",
          "November",
          "Desember",
        ];
        return `${now.getDate()} ${bulan[now.getMonth()]} ${now.getFullYear()}`;
      }
      function getDayTimeString() {
        const now = new Date();
        const hari = [
          "Minggu",
          "Senin",
          "Selasa",
          "Rabu",
          "Kamis",
          "Jumat",
          "Sabtu",
        ];
        return `${hari[now.getDay()]}, ${pad2(now.getHours())}:${pad2(now.getMinutes())}:${pad2(now.getSeconds())}`;
      }
      const headerSlides = [
        () => "SAFETY DELIVERY REPORT",
        getDateString,
        getDayTimeString,
      ];
      let headerSlideIdx = 0;
      const headerSlider = document.getElementById("headerSlider");
      let isAnimating = false;
      function updateHeaderSlideSmooth() {
        if (isAnimating) return;
        isAnimating = true;
        // Slide out
        headerSlider.classList.remove("slide-in");
        headerSlider.classList.add("slide-out");
        setTimeout(() => {
          // Update text
          headerSlider.innerText = headerSlides[headerSlideIdx % 3]();
          headerSlider.classList.remove("slide-out");
          headerSlider.classList.add("slide-in");
          headerSlideIdx = (headerSlideIdx + 1) % 3;
          setTimeout(() => {
            isAnimating = false;
          }, 700); // Wait for slide-in animation to finish
        }, 700);
      }
      // Initial state
      headerSlider.innerText = headerSlides[0]();
      headerSlider.classList.add("slide-in");
      // Cycle every 3.5s
      setInterval(updateHeaderSlideSmooth, 3500);
      // For live time update on time slide
      setInterval(() => {
        // Only update if currently on time slide
        if ((headerSlideIdx + 2) % 3 === 2 && !isAnimating) {
          headerSlider.innerText = headerSlides[2]();
        }
      }, 1000);
      /* ===== DATA HARI INI (DUMMY) ===== */
      // Tampilkan hari, tanggal, bulan, tahun di bawah judul
      function tampilkanTanggalLengkap() {
        const hari = [
          "Minggu",
          "Senin",
          "Selasa",
          "Rabu",
          "Kamis",
          "Jumat",
          "Sabtu",
        ];
        const bulan = [
          "Januari",
          "Februari",
          "Maret",
          "April",
          "Mei",
          "Juni",
          "Juli",
          "Agustus",
          "September",
          "Oktober",
          "November",
          "Desember",
        ];
        const now = new Date();
        const namaHari = hari[now.getDay()];
        const tanggal = now.getDate();
        const namaBulan = bulan[now.getMonth()];
        const tahun = now.getFullYear();
        const strTanggal = `${namaHari}, ${tanggal} ${namaBulan} ${tahun}`;
        var el = document.getElementById("tanggalLengkap");
        if (el) el.innerText = strTanggal;
      }
      document.addEventListener("DOMContentLoaded", tampilkanTanggalLengkap);

      // Demo users for animation
      const demoUsers = [
        { platno: "F 9101 DEF", status: [22, 0, 2] }, // Set as initial user
        { platno: "B 1234 XYZ", status: [18, 2, 1] },
        { platno: "D 5678 ABC", status: [15, 4, 0] },
        { platno: "H 2345 GHI", status: [12, 3, 3] },
        { platno: "BK 6789 JKL", status: [20, 1, 0] },
      ];
      let demoIdx = 0;

      function animatePlatnoAndStatus(newPlatno, newStatus) {
        const platnoValue = document.querySelector(".platno .value");
        if (platnoValue) {
          // Fade out
          platnoValue.classList.remove("platno-enter");
          platnoValue.classList.add("platno-exit");
          setTimeout(() => {
            platnoValue.classList.remove("platno-exit");
            platnoValue.innerText = newPlatno;
            // Fade in
            platnoValue.classList.add("platno-enter");
            setTimeout(() => {
              platnoValue.classList.remove("platno-enter");
            }, 850);
          }, 850);
        }
        ["okCount", "ngCount", "unknownCount"].forEach((id, idx) => {
          const el = document.getElementById(id);
          if (el) {
            // Fade out
            el.classList.remove("status-value-enter");
            el.classList.add("status-value-exit");
            setTimeout(() => {
              el.classList.remove("status-value-exit");
              el.innerText = newStatus[idx];
              // Fade in
              el.classList.add("status-value-enter");
              setTimeout(() => {
                el.classList.remove("status-value-enter");
              }, 850);
            }, 850);
          }
        });
      }

      // Set initial values
      animatePlatnoAndStatus(demoUsers[0].platno, demoUsers[0].status);

      // Cycle through demo users every 4.8 seconds
      setInterval(() => {
        demoIdx = (demoIdx + 1) % demoUsers.length;
        animatePlatnoAndStatus(
          demoUsers[demoIdx].platno,
          demoUsers[demoIdx].status,
        );
      }, 4800);

      /* ===== LINE CHART BULANAN ===== */
      var optionsLine = {
        chart: {
          height: 328,
          type: "line",
          zoom: {
            enabled: false,
          },
          dropShadow: {
            enabled: true,
            top: 3,
            left: 2,
            blur: 4,
            opacity: 1,
          },
        },
        stroke: {
          curve: "smooth",
          width: 2,
        },
        colors: ["#2ecc71", "#e74c3c", "#f1c40f"], // green for OK, red for NG, yellow for ?
        series: [
          {
            name: "OK",
            data: [1, 15, 26, 20, 33, 27],
          },
          {
            name: "NG",
            data: [3, 33, 21, 42, 19, 32],
          },
          {
            name: "?",
            data: [0, 39, 52, 11, 29, 43],
          },
        ],
        title: {
          text: "daily chart for this month",
          align: "left",
          offsetY: 25,
          offsetX: 20,
        },
        subtitle: {
          text: "Statistics",
          offsetY: 55,
          offsetX: 20,
        },
        markers: {
          size: 6,
          strokeWidth: 0,
          hover: {
            size: 9,
          },
        },
        grid: {
          show: true,
          padding: {
            bottom: 0,
          },
        },
        labels: [
          "01/15/2002",
          "01/16/2002",
          "01/17/2002",
          "01/18/2002",
          "01/19/2002",
          "01/20/2002",
        ],
        xaxis: {
          tooltip: {
            enabled: false,
          },
        },
        legend: {
          position: "top",
          horizontalAlign: "right",
          offsetY: -20,
        },
      };

      /* ===== PIE CHART HARIAN ===== */
      var optionsPie = {
        chart: {
          type: "donut",
          height: 260,
        },
        series: demoUsers[0].status,
        labels: ["OK", "NG", "?"],
        colors: ["#2ecc71", "#e74c3c", "#f1c40f"],
        plotOptions: {
          pie: {
            donut: {
              size: "65%",
              labels: {
                show: true,
                total: {
                  show: true,
                  label: "Today",
                },
              },
            },
          },
        },
        legend: {
          position: "bottom",
        },
      };

      new ApexCharts(
        document.querySelector("#pieChartInner"),
        optionsPie,
      ).render();

      // Render the line chart
      new ApexCharts(
        document.querySelector("#lineChart"),
        optionsLine,
      ).render();