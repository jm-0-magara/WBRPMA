// rgb to hex convert
function rgbToHex(rgb) {
    // Extract RGB values using regular expressions
    const rgbValues = rgb.match(/\d+/g);

    if (rgbValues.length === 3) {
        var [r, g, b] = rgbValues.map(Number);
    }
    // Ensure the values are within the valid range (0-255)
    r = Math.max(0, Math.min(255, r));
    g = Math.max(0, Math.min(255, g));
    b = Math.max(0, Math.min(255, b));

    // Convert each component to its hexadecimal representation
    const rHex = r.toString(16).padStart(2, '0');
    const gHex = g.toString(16).padStart(2, '0');
    const bHex = b.toString(16).padStart(2, '0');

    // Combine the hexadecimal values with the "#" prefix
    const hexColor = `#${rHex}${gHex}${bHex}`;

    return hexColor.toUpperCase(); // Convert to uppercase for consistency
}

// common function to get charts colors from class
function getChartColorsArray(chartId) {
    const chartElement = document.getElementById(chartId);
    if (chartElement) {
        const colors = chartElement.dataset.chartColors;
        if (colors) {
            const parsedColors = JSON.parse(colors);
            const mappedColors = parsedColors.map((value) => {
                const newValue = value.replace(/\s/g, "");
                if (!newValue.includes("#")) {
                    const element = document.querySelector(newValue);
                    if (element) {
                        const styles = window.getComputedStyle(element);
                        const backgroundColor = styles.backgroundColor;
                        return backgroundColor || newValue;
                    } else {
                        const divElement = document.createElement('div');
                        divElement.className = newValue;
                        document.body.appendChild(divElement);

                        const styles = window.getComputedStyle(divElement);
                        const backgroundColor = styles.backgroundColor.includes("#") ? styles.backgroundColor : rgbToHex(styles.backgroundColor);
                        return backgroundColor || newValue;
                    }
                } else {
                    return newValue;
                }
            });
            return mappedColors;
        } else {
            console.warn(`chart-colors attribute not found on: ${chartId}`);
        }
    }
}

var totalGrossProfitChart = "";
var totalExpenditureChart = "";
var totalNetProfitChart = "";
var totalDebtsChart = "";
var profitsChart = "";

function loadCharts() {
    document.addEventListener('DOMContentLoaded', function () {
        var totalGrossProfitColors = getChartColorsArray("totalGrossProfit");
        var totalExpenditureColors = getChartColorsArray("totalExpenditure");
    
        // Render Total Gross Profit Chart
        if (totalGrossProfitColors) {
            var optionsGrossProfit = {
                series: [percentageIncrease],
                chart: {
                    height: 110,
                    type: 'radialBar',
                    sparkline: {
                        enabled: true
                    }
                },
                plotOptions: {
                    radialBar: {
                        hollow: {
                            margin: 0,
                            size: '50%',
                        },
                        track: {
                            margin: 2,
                        },
                        dataLabels: {
                            show: false
                        }
                    }
                },
                grid: {
                    padding: {
                        top: -15,
                        bottom: -15
                    }
                },
                stroke: {
                    lineCap: 'round'
                },
                labels: ['Total Employee'],
                colors: totalGrossProfitColors
            };
    
            var totalGrossProfitChart = new ApexCharts(document.querySelector("#totalGrossProfit"), optionsGrossProfit);
            totalGrossProfitChart.render();
    
            // Update the text color based on percentage increase
            var percentageIncreaseText = document.getElementById('percentageIncreaseText');
            var percentageIncreaseValue = document.getElementById('percentageIncreaseValue');
    
            if (percentageIncrease < 0) {
                percentageIncreaseValue.classList.remove('text-green-500');
                percentageIncreaseValue.classList.add('text-red-500');
                percentageIncreaseText.innerHTML = `<span class="font-medium text-red-500">${percentageIncrease}%</span> Decrease`;
            } else {
                percentageIncreaseValue.classList.remove('text-red-500');
                percentageIncreaseValue.classList.add('text-green-500');
                percentageIncreaseText.innerHTML = `<span class="font-medium text-green-500">${percentageIncrease}%</span> Increase`;
            }
        }
    
        // Render Total Expenditure Chart
        if (totalExpenditureColors) {
            var optionsExpenditure = {
                series: [percentageDecrease],
                chart: {
                    height: 110,
                    type: 'radialBar',
                    sparkline: {
                        enabled: true
                    }
                },
                plotOptions: {
                    radialBar: {
                        hollow: {
                            margin: 0,
                            size: '50%',
                        },
                        track: {
                            margin: 2,
                        },
                        dataLabels: {
                            show: false
                        }
                    }
                },
                grid: {
                    padding: {
                        top: -15,
                        bottom: -15
                    }
                },
                stroke: {
                    lineCap: 'round'
                },
                labels: ['Total Employee'], // Replace with appropriate label
                colors: totalExpenditureColors
            };
    
            var totalExpenditureChart = new ApexCharts(document.querySelector("#totalExpenditure"), optionsExpenditure);
            totalExpenditureChart.render();
    
            // Update the text color based on percentage decrease
            var percentageDecreaseText = document.getElementById('percentageDecreaseText');
            var percentageDecreaseValue = document.getElementById('percentageDecreaseValue');
    
            if (percentageDecrease < 0) {
                percentageDecreaseValue.classList.remove('text-red-500');
                percentageDecreaseValue.classList.add('text-green-500');
                percentageDecreaseText.innerHTML = `<span class="font-medium text-green-500">${percentageDecrease}%</span> Decrease`;
            } else {
                percentageDecreaseValue.classList.remove('text-green-500');
                percentageDecreaseValue.classList.add('text-red-500');
                percentageDecreaseText.innerHTML = `<span class="font-medium text-red-500">${percentageDecrease}%</span> Decrease`;
            }
        }
    });

    // Total Net Profits
    var totalNetProfitColors = "";
    totalNetProfitColors = getChartColorsArray("totalNetProfit");
    if (totalNetProfitColors) {
        var options = {
            series: [25],
            chart: {
                height: 110,
                type: 'radialBar',
                sparkline: {
                    enabled: true
                }
            },
            plotOptions: {
                radialBar: {
                    hollow: {
                        margin: 0,
                        size: '50%',
                    },
                    track: {
                        margin: 2,
                    },
                    dataLabels: {
                        show: false
                    }
                }
            },
            grid: {
                padding: {
                    top: -15,
                    bottom: -15
                }
            },
            stroke: {
                lineCap: 'round'
            },
            labels: ['Total Employee'],
            colors: totalNetProfitColors
        };

        if (totalNetProfitChart != "")
            totalNetProfitChart.destroy();
        totalNetProfitChart = new ApexCharts(document.querySelector("#totalNetProfit"), options);
        totalNetProfitChart.render();
    }

    //  Total Debts
    var totalDebtsColors = "";
    totalDebtsColors = getChartColorsArray("totalDebts");
    if (totalDebtsColors) {
        var options = {
            series: [75],
            chart: {
                height: 110,
                type: 'radialBar',
                sparkline: {
                    enabled: true
                }
            },
            plotOptions: {
                radialBar: {
                    hollow: {
                        margin: 0,
                        size: '50%',
                    },
                    track: {
                        margin: 2,
                    },
                    dataLabels: {
                        show: false
                    }
                }
            },
            grid: {
                padding: {
                    top: -15,
                    bottom: -15
                }
            },
            stroke: {
                lineCap: 'round'
            },
            labels: ['Total Employee'],
            colors: totalDebtsColors
        };

        if (totalDebtsChart != "")
            totalDebtsChart.destroy();
        totalDebtsChart = new ApexCharts(document.querySelector("#totalDebts"), options);
        totalDebtsChart.render();
    }

    //PROFITS Received
    document.addEventListener('DOMContentLoaded', function () {
        var profitsColors = getChartColorsArray("profitsChart");
        
        if (profitsColors) {
            // Function to fetch data from the Laravel endpoint
            function fetchData() {
                return fetch('/api/payments')
                    .then(response => response.json())
                    .then(data => {
                        let grossAmounts = data.map(payment => payment.total_amount);
                        let netAmounts = data.map(payment => payment.net_profit);
                        let months = data.map(payment => payment.month);
                        return { grossAmounts, netAmounts, months };
                    });
            }
    
            // Function to initialize the chart
            function initializeChart(data) {
                var options = {
                    series: [{
                        name: 'Gross Profit',
                        type: 'area',
                        data: data.grossAmounts
                    }, {
                        name: 'Net Profit',
                        type: 'line',
                        data: data.netAmounts
                    }],
                    chart: {
                        height: 315,
                        type: 'line',
                        stacked: false,
                        margin: {
                            left: 0,
                            right: 0,
                            top: 0,
                            bottom: 0
                        },
                        toolbar: {
                            show: false,
                        },
                    },
                    stroke: {
                        width: [2, 2],
                        curve: 'smooth'
                    },
                    fill: {
                        opacity: [0.03, 1],
                        gradient: {
                            inverseColors: false,
                            shade: 'light',
                            type: "vertical",
                            opacityFrom: 0.85,
                            opacityTo: 0.55,
                            stops: [0, 100, 100, 100]
                        }
                    },
                    labels: data.months,
                    colors: profitsColors,
                    markers: {
                        size: 0
                    },
                    grid: {
                        padding: {
                            top: -15,
                            right: 0,
                        }
                    },
                    tooltip: {
                        shared: true,
                        intersect: false,
                        y: {
                            formatter: function (y) {
                                if (typeof y !== "undefined") {
                                    return y.toFixed(0) + " points";
                                }
                                return y;
                            }
                        }
                    }
                };
    
                var profitsChart = new ApexCharts(document.querySelector("#profitsChart"), options);
                profitsChart.render();
            }
    
            // Fetch data and initialize the chart
            fetchData().then(data => initializeChart(data));
        }
    });
}

window.addEventListener("resize", function () {
    setTimeout(() => {
        loadCharts();
    }, 0);
});
loadCharts();

document.addEventListener('DOMContentLoaded', function () {
    var totalExpenditureTableColors = getChartColorsArray("totalExpenditureTable");

    if (totalExpenditureTableColors) {
        var options = {
            series: [{
                name: 'Expenditure',
                data: amounts
            }],
            chart: {
                type: 'bar',
                height: 350,
                stacked: true,
                zoom: {
                    enabled: true
                },
                toolbar: {
                    show: false,
                },
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    borderRadius: 2,
                    columnWidth: '25%',
                },
            },
            grid: {
                padding: {
                    top: -15,
                    bottom: 5,
                    right: 0,
                }
            },
            xaxis: {
                categories: months,
            },
            dataLabels: {
                enabled: false
            },
            colors: totalExpenditureTableColors,
            legend: {
                position: 'bottom',
            },
            fill: {
                opacity: 1
            }
        };

        var chart = new ApexCharts(document.querySelector("#totalExpenditureTable"), options);
        chart.render();
    }
});

const options44 = {
    settings: {
        visibility: {
            theme: 'light',
        },
        selected: {
            month: 10,
            year: 2023,
        },
    },
    popups: {
        '2023-06-28': {
            modifier: '!bg-red-500 !text-white',
            html: 'Meeting with Designer',
        },
        '2023-07-13': {
            modifier: '!bg-red-500 !text-white text-bold',
            html: 'Meeting at 6:00 PM',
        },
        '2023-07-08': {
            modifier: '!bg-purple-500 !text-white text-bold',
            html: `<div>
        <div class="font-semibold underline mb-1">09:57 AM</div>
        <p class="text-slate-500 dark:text-zink-200">Developing Line Managers Conference</p>
      </div>`,
        },
        '2023-07-17': {
            modifier: '!bg-green-500 !text-white',
            html: `<div>
        <div class="font-semibold underline mb-1">12:00 PM</div>
        <p class="text-slate-500 dark:text-zink-200">Airplane in Las Vegas</p>
      </div>`,
        },
        '2023-11-11': {
            modifier: '!bg-purple-500 !text-white text-bold',
            html: `<div>
        <div class="font-semibold underline mb-1">09:57 AM</div>
        <p class="text-slate-500 dark:text-zink-200">Leadership Executive Summit</p>
      </div>`,
        },
        '2023-11-11': {
            modifier: '!bg-orange-500 !text-white text-bold',
            html: `<div>
        <p class="text-slate-500 dark:text-zink-200">Hospitality Project Discuses</p>
      </div>`,
        },
    },
};

const calendar = new VanillaCalendar('#calendar', options44);
calendar.init();
