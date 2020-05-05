/*
    Insurance: Item Tracker
    Copyright (C) 2020 Michael Cabot
*/

/*
    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <https://www.gnu.org/licenses/>.
*/

var barChart;
var pieChart;

function loadCharts() {
    $.getJSON('includes/items.json.php', { type: "stats" }, function(data) {
        var totalItemsReplaced = data.replaced.total + data.submitted.total + data.finalized.total;
        var notReadyToCollect = data.notreplaced.money + data.partial.money;
        pieChart = new Chart(document.getElementById("pie-chart"), {
            type: 'pie',
            data: {
                labels: ["Not Replaced", "Partial", "Replaced"],
				datasets: [{
					data: [data.notreplaced.total,data.partial.total,totalItemsReplaced],
                    borderColor: "red",
                    borderWidth: "1",
                    hoverBorderWidth: "3",
					backgroundColor: ["rgba(255,135,148,0.3)", "rgba(255,165,5,0.3)", "rgba(205,83,5,0.3)"],
					hoverBackgroundColor: ["rgba(255,135,148,0.6)", "rgba(255,165,5,0.6)", "rgba(205,83,5,0.6)"]
				}]
			},
			options: {
                maintainAspectRatio: false,
				legend: {
					display: true,
					position: 'top',
					labels: {
						fontFamily: "'techna_sansregular', 'Verdana', 'sans-serif'",
						fontSize: 16
					}
				},
				title: {
					display: true,
					text: 'Items Replaced',
					fontFamily: "'techna_sansregular', 'Verdana', 'sans-serif'",
					fontSize: 20
				}
			}
		});
    
        barChart = new Chart(document.getElementById("bar-chart"), {
            type: 'bar',
            data: {
                labels: ["Not Ready", "Ready", "Pending", "Collected"],
                datasets: [{
                    label: "Amount ($)",
                    borderColor: "red",
                    borderWidth: "1",
                    hoverBorderWidth: "3",
                    hoverBackgroundColor: ["rgba(255,135,148,0.6)","rgba(165,228,255,0.6)","rgba(165,228,0,0.6)","rgba(0,255,0,0.6)"],
                    backgroundColor: ["rgba(255,135,148,0.3)","rgba(165,228,255,0.3)","rgba(165,228,0,0.3)","rgba(0,255,0,0.3)"],
                    data: [notReadyToCollect.toFixed(2), data.replaced.money.toFixed(2), data.submitted.money.toFixed(2), data.finalized.money.toFixed(2)]
                }]
            },
            options: {
                maintainAspectRatio: false,
                legend: { display: false },
                title: {
                    display: true,
                    text: 'Money Collection',
                    fontFamily: "'techna_sansregular', 'Verdana', 'sans-serif'",
                    fontSize: 20
                },

                scales: {
                    xAxes: [{
                        gridLines: {
                            display: false
                        }
                    }],
                    yAxes: [{
                        gridLines: {
                            color: "rgba(255,99,132,0.2)"
                        }
                    }]
                },
            }
        });
    });
}

$(document).ready(function() {
    loadCharts();
});