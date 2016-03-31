var loader_options = {
	maskOpacity: .6,
	overlay: {
		show: true
	}

};
$(document).ready(function() {

	$('.projects_table_json').each(
		function(index, item) {
			//Find the url
			var url = $(item).find(".json_url").text();
			$.ajax({
				url: url,
				context: $(item)
			}).done(function(data) {
					$(data).each(
						function(index, user) {

							var table = $(item).find('table');
							var tr = $('#tr_ranking').clone();

							$(tr).find('.pad-nombre').text(user.name);
							$(tr).find('.pad-progress .progress div').addClass('progress-bar-' + user.class);
							$(tr).find('.badge').text(user.value);
							$(tr).find('.pad-nro').find('span').addClass('badge bg-' + user.color);
							$(tr).find('.progress div').css('width', user.value + '%');
							$(tr).attr('id', user.id);
							$(tr).find('img').attr('src', user.avatar);
							$(tr).removeClass('hidden');
							$(table).append(tr);
						}
					);
				}
				//---data contains all the parameters needed
			);
			//para mi la estructura de la vista no es valida.. es un array de parser.. se puede eso?
		});

	$('.team_ranking_json').each(
		function(index, item) {
			//Find the url
			var url = $(item).find(".json_url").text();
			$.ajax({
				url: url,
				context: $(item)
			}).done(function(data) {
					$(data).each(
						function(index, user) {

							var table = $(item).find('table');
							var tr = $('#tr_ranking').clone();

							$(tr).find('.pad-nombre').text(user.name);
							$(tr).find('.pad-progress .progress div').addClass('progress-bar-' + user.class);
							$(tr).find('.badge').text(user.value);
							$(tr).find('.pad-nro').find('span').addClass('badge bg-' + user.color);
							$(tr).find('.progress div').css('width', user.value + '%');
							$(tr).attr('id', user.id);
							$(tr).find('img').attr('src', user.avatar);
							$(tr).removeClass('hidden');
							$(table).append(tr);
						}
					);
				}
				//---data contains all the parameters needed
			);
			//para mi la estructura de la vista no es valida.. es un array de parser.. se puede eso?
		});


	// $('.data_lines').each(
	//  function(index, item) {
	//   //Find the url
	//   var url = $(item).find(".json_url").text();
	//   $.ajax({
	//    url: url,
	//    context: $(item)
	//   }).done(function(data) {
	//     //----
	//    $.plot(".data_lines", [line_data1, line_data2], {});
	//    }
	//    //---data contains all the parameters needed
	//   );
	//  });

	$('.data_bars').each(
		function(index, item) {
			//Find the url
			var url = $(item).find(".json_url").text();
			//----check if bload exists
			if($.fn.bload) 
			var bload=$(item).bload(loader_options);
			
			$.ajax({
				url: url,
				context: $(item)
			}).done(function(data) {
				var config = {
						grid: {
							hoverable: true,
							borderWidth: 1,
							borderColor: "#f3f3f3",
							tickColor: "#f3f3f3"
						},
						series: {
							bars: {
								show: true,
								barWidth: 0.5,
								align: "center"
							}
						},
						xaxis: {
							mode: "categories",
							tickLength: 0
						}
					}
					// $.plot((item).attr('id'), [bar_data], {});

				$.plot(item, data.data, config);
			    $('.data_bars').UseTooltip(item);
				if(bload)
					bload.hide();
			});

		});
	
	
	var previousPoint = null, previousLabel = null;
		
	$.fn.UseTooltip = function () {
            $(this).bind("plothover", function (event, pos, item) {
                if (item) {
                    if ((previousLabel != item.series.label) || (previousPoint != item.dataIndex)) {
                        previousPoint = item.dataIndex;
                        previousLabel = item.series.label;
                        $("#tooltip").remove();
 
                        var x = item.datapoint[0];
                        var y = item.datapoint[1];
 
                        var color = item.series.color;
 
                  //      console.log(item.series.xaxis.ticks[x].label);                
 
                        showTooltip(item.pageX,
                        item.pageY,
                        color,
                        "<strong>" + item.series.label + "</strong><br>" + item.series.xaxis.ticks[x].label + " : <strong>" + y + "</strong> °C");
                    }
                } else {
                    $(".tooltip").remove();
                    previousPoint = null;
                }
            });
        };
    
    function showTooltip(x, y, color, contents) {
            $('<div class="tooltip">' + contents + '</div>').css({
                position: 'absolute',
                display: 'none',
                top: y - 40,
                left: x - 120,
                border: '2px solid ' + color,
                padding: '3px',
                'font-size': '9px',
                'border-radius': '5px',
                'background-color': '#fff',
                'font-family': 'Verdana, Arial, Helvetica, Tahoma, sans-serif',
                opacity: 0.9
            }).appendTo("body").fadeIn(200);
        }    


	$(function() {
		/*
		 * LINE CHART
		 * ----------
		 */
		//LINE randomly generated data

		for (var i = 0; i < 28; i += 0.5) {
			//      sin.push([i, Math.sin(i)]);
			//     cos.push([i, Math.cos(i)]);
		}
		//Initialize tooltip on hover

		/* END LINE CHART */
		/*
		/*
			* BAR CHART
			* ---------
			*/
		// $.plot("#bar-chart", [bar_data], {
		//     grid: {
		//         borderWidth: 1,
		//         borderColor: "#f3f3f3",
		//         tickColor: "#f3f3f3"
		//     },
		//     series: {
		//         bars: {
		//             show: true,
		//             barWidth: 0.5,
		//             align: "center"
		//         }
		//     },
		//     xaxis: {
		//         mode: "categories",
		//         tickLength: 0
		//     }
		// });
		/* END BAR CHART */
		// });

		/*
		 * Custom Label formatter
		 * ----------------------
		 */
		function labelFormatter(label, series) {
			return "<div style='font-size:13px; text-align:center; padding:2px; color: #fff; font-weight: 600;'>" + label + "<br/>" + Math.round(series.percent) + "%</div>";
		}

		$(function() {
			/* jQueryKnob */

			$(".knob").knob({
				/*change : function (value) {
					//console.log("change : " + value);
					},
					release : function (value) {
					console.log("release : " + value);
					},
					cancel : function () {
					console.log("cancel : " + this.value);
					},*/
				draw: function() {

					// "tron" case
					if (this.$.data('skin') == 'tron') {

						var a = this.angle(this.cv) // Angle
							,
							sa = this.startAngle // Previous start angle
							,
							sat = this.startAngle // Start angle
							,
							ea // Previous end angle
							, eat = sat + a // End angle
							,
							r = true;

						this.g.lineWidth = this.lineWidth;

						this.o.cursor && (sat = eat - 0.3) && (eat = eat + 0.3);

						if (this.o.displayPrevious) {
							ea = this.startAngle + this.angle(this.value);
							this.o.cursor && (sa = ea - 0.3) && (ea = ea + 0.3);
							this.g.beginPath();
							this.g.strokeStyle = this.previousColor;
							this.g.arc(this.xy, this.xy, this.radius - this.lineWidth, sa, ea, false);
							this.g.stroke();
						}

						this.g.beginPath();
						this.g.strokeStyle = r ? this.o.fgColor : this.fgColor;
						this.g.arc(this.xy, this.xy, this.radius - this.lineWidth, sat, eat, false);
						this.g.stroke();

						this.g.lineWidth = 2;
						this.g.beginPath();
						this.g.strokeStyle = this.o.fgColor;
						this.g.arc(this.xy, this.xy, this.radius - this.lineWidth + 1 + this.lineWidth * 2 / 3, 0, 2 * Math.PI, false);
						this.g.stroke();

						return false;
					}
				}
			});
			/* END JQUERY KNOB */

			//INITIALIZE SPARKLINE CHARTS
			$(".sparkline").each(function() {
				var $this = $(this);
				$this.sparkline('html', $this.data());
			});

			/* SPARKLINE DOCUMENTAION EXAMPLES http://omnipotent.net/jquery.sparkline/#s-about */
			drawDocSparklines();
			drawMouseSpeedDemo();

		});

		function drawDocSparklines() {

			// Bar + line composite charts
			$('#compositebar').sparkline('html', {
				type: 'bar',
				barColor: '#aaf'
			});
			$('#compositebar').sparkline([4, 1, 5, 7, 9, 9, 8, 7, 6, 6, 4, 7, 8, 4, 3, 2, 2, 5, 6, 7], {
				composite: true,
				fillColor: false,
				lineColor: 'red'
			});


			// Line charts taking their values from the tag
			$('.sparkline-1').sparkline();

			// Larger line charts for the docs
			$('.largeline').sparkline('html', {
				type: 'line',
				height: '2.5em',
				width: '4em'
			});

			// Customized line chart
			$('#linecustom').sparkline('html', {
				height: '1.5em',
				width: '8em',
				lineColor: '#f00',
				fillColor: '#ffa',
				minSpotColor: false,
				maxSpotColor: false,
				spotColor: '#77f',
				spotRadius: 3
			});

			// Bar charts using inline values
			$('.sparkbar').sparkline('html', {
				type: 'bar'
			});

			$('.barformat').sparkline([1, 3, 5, 3, 8], {
				type: 'bar',
				tooltipFormat: '{{value:levels}} - {{value}}',
				tooltipValueLookups: {
					levels: $.range_map({
						':2': 'Low',
						'3:6': 'Medium',
						'7:': 'High'
					})
				}
			});

			// Tri-state charts using inline values
			$('.sparktristate').sparkline('html', {
				type: 'tristate'
			});
			$('.sparktristatecols').sparkline('html', {
				type: 'tristate',
				colorMap: {
					'-2': '#fa7',
					'2': '#44f'
				}
			});

			// Composite line charts, the second using values supplied via javascript
			$('#compositeline').sparkline('html', {
				fillColor: false,
				changeRangeMin: 0,
				chartRangeMax: 10
			});
			$('#compositeline').sparkline([4, 1, 5, 7, 9, 9, 8, 7, 6, 6, 4, 7, 8, 4, 3, 2, 2, 5, 6, 7], {
				composite: true,
				fillColor: false,
				lineColor: 'red',
				changeRangeMin: 0,
				chartRangeMax: 10
			});

			// Line charts with normal range marker
			$('#normalline').sparkline('html', {
				fillColor: false,
				normalRangeMin: -1,
				normalRangeMax: 8
			});
			$('#normalExample').sparkline('html', {
				fillColor: false,
				normalRangeMin: 80,
				normalRangeMax: 95,
				normalRangeColor: '#4f4'
			});

			// Discrete charts
			$('.discrete1').sparkline('html', {
				type: 'discrete',
				lineColor: 'blue',
				xwidth: 18
			});
			$('#discrete2').sparkline('html', {
				type: 'discrete',
				lineColor: 'blue',
				thresholdColor: 'red',
				thresholdValue: 4
			});

			// Bullet charts
			$('.sparkbullet').sparkline('html', {
				type: 'bullet'
			});

			// Pie charts
			$('.sparkpie').sparkline('html', {
				type: 'pie',
				height: '1.0em'
			});

			// Box plots
			$('.sparkboxplot').sparkline('html', {
				type: 'box'
			});
			$('.sparkboxplotraw').sparkline([1, 3, 5, 8, 10, 15, 18], {
				type: 'box',
				raw: true,
				showOutliers: true,
				target: 6
			});

			// Box plot with specific field order
			$('.boxfieldorder').sparkline('html', {
				type: 'box',
				tooltipFormatFieldlist: ['med', 'lq', 'uq'],
				tooltipFormatFieldlistKey: 'field'
			});

			// click event demo sparkline
			$('.clickdemo').sparkline();
			$('.clickdemo').bind('sparklineClick', function(ev) {
				var sparkline = ev.sparklines[0],
					region = sparkline.getCurrentRegionFields();
				value = region.y;
				alert("Clicked on x=" + region.x + " y=" + region.y);
			});

			// mouseover event demo sparkline
			$('.mouseoverdemo').sparkline();
			$('.mouseoverdemo').bind('sparklineRegionChange', function(ev) {
				var sparkline = ev.sparklines[0],
					region = sparkline.getCurrentRegionFields();
				value = region.y;
				$('.mouseoverregion').text("x=" + region.x + " y=" + region.y);
			}).bind('mouseleave', function() {
				$('.mouseoverregion').text('');
			});
		}

		/**
		 ** Draw the little mouse speed animated graph
		 ** This just attaches a handler to the mousemove event to see
		 ** (roughly) how far the mouse has moved
		 ** and then updates the display a couple of times a second via
		 ** setTimeout()
		 **/
		function drawMouseSpeedDemo() {
			var mrefreshinterval = 500; // update display every 500ms
			var lastmousex = -1;
			var lastmousey = -1;
			var lastmousetime;
			var mousetravel = 0;
			var mpoints = [];
			var mpoints_max = 30;
			$('html').mousemove(function(e) {
				var mousex = e.pageX;
				var mousey = e.pageY;
				if (lastmousex > -1) {
					mousetravel += Math.max(Math.abs(mousex - lastmousex), Math.abs(mousey - lastmousey));
				}
				lastmousex = mousex;
				lastmousey = mousey;
			});
			var mdraw = function() {
					var md = new Date();
					var timenow = md.getTime();
					if (lastmousetime && lastmousetime != timenow) {
						var pps = Math.round(mousetravel / (timenow - lastmousetime) * 1000);
						mpoints.push(pps);
						if (mpoints.length > mpoints_max)
							mpoints.splice(0, 1);
						mousetravel = 0;
						$('#mousespeed').sparkline(mpoints, {
							width: mpoints.length * 2,
							tooltipSuffix: ' pixels per second'
						});
					}
					lastmousetime = timenow;
					setTimeout(mdraw, mrefreshinterval);
				}
				// We could use setInterval instead, but I prefer to do it this way
			setTimeout(mdraw, mrefreshinterval);
		}
	});
	//----end document ready
});