var sale = [];
$(document).ready(function(){
	$.ajax({
		url: "http://localhost/inventory%20final/app/salesdata.php",
		type: "GET",
		success: function(data){
			var sales = [];
			for (var i = 0; i < data.length; i++) {
				var color = "#007bff";
				sales.push(data[i].total);
				sale.push(data[i].date_purchased);
			}
			console.log(sale);
			var ctx = $("#canvas");
			var ctx2 = $("#canvas2");
			var data = {
				labels: sale,
				datasets: [
					{
						label: "Sales",
						data: sales,
						backgroundColor: color,
						borderColor: color,
						fill: false,
						lineTension: 0.25,
						pointRadius: 5
					}
				]
			};
			var options = {
				title: {
					display: true,
					position: "top",
					text: "Sales Progress Report",
					fontSize: 18,
					fontColor: color
				},
				legend: {
					display: true,
					position: "bottom"
				},
				scales: {
					yAxes: [
						{
							ticks: {
								beginAtZero: true
							}
						}
					],
					xAxes: [
						{
							ticks: {
								beginAtZero: true
							}
						}
					]
				}
			}
			var chart = new Chart(ctx,{
				type: "line",
				data: data,
				options: options
			});
			var chart = new Chart(ctx2,{
				type: "bar",
				data: data,
				options: options
			});
		},
		error: function(data){
			console.log(data);
		}
	});
});