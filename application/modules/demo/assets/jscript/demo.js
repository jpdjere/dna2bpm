    $(document).ready(function() {

   //    $('#button').click(function() {
  
   //     tour.start();
   //    });






           var sin = [[0,0],[2,2],[4,4],[6,6],[8,8],[10,10],[12,12],[14,14],[16,16],[18,18],[20,20],[22,22],[24,24],[26,26],[28,28]];
               cos = [[0,0],[2,0],[4,1],[6,1],[8,1],[10,1],[12,2],[14,2],[16,2],[18,2],[20,2],[22,3],[24,3],[26,3],[28,4]];

           var line_data1 = {
               data: sin,
               color: "#3c8dbc"
           };
           var line_data2 = {
               data: cos,
               color: "#00c0ef"
           };
           var bar_data = {
               data: [
                   ["Semana 1", 10],
                   ["Semana 2", 8],
                   ["Semana 3", 6],
                   ["Semana 4", 8],
                   ["Semana 5", 10]
               ],
               color: "#3c8dbc"
           };
           
           
           
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
       //     function(index, item) {
       //         //Find the url
       //         var url = $(item).find(".json_url").text();
       //         $.ajax({
       //             url: url,
       //             context: $(item)
       //         }).done(function(data) {
       //                  //----
       //                  $.plot(item.attr('id'), data, {});
                       
       //                 //---- esto de abajo para que?
       //             }
       //             //---data contains all the parameters needed
       //         );
             
       //     });           

       // $('.data_bars').each(
       //     function(index, item) {
       //         //Find the url
       //         var url = $(item).find(".json_url").text();
       //         $.ajax({
       //             url: url,
       //             context: $(item)
       //         }).done(function(data) {
                
       //          $.plot(item.attr('id'), data, {});
       //         }
       //             //---data contains all the parameters needed
       //         );
               
       //     });
           
           
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
           
         
           
           $.plot("#line-chart", [line_data1, line_data2], {
               grid: {
                   hoverable: true,
                   borderColor: "#f3f3f3",
                   borderWidth: 1,
                   tickColor: "#f3f3f3"
               },
               series: {
                   shadowSize: 0,
                   lines: {
                       show: true
                   },
                   points: {
                       show: true
                   }
               },
               lines: {
                   fill: false,
                   color: ["#3c8dbc", "#f56954"]
               },
               yaxis: {
                   show: true,
               },
               xaxis: {
                   show: true
               }
           });

           //Initialize tooltip on hover

           /* END LINE CHART */
           /*
           /*
            * BAR CHART
            * ---------
            */

           $.plot("#bar-chart", [bar_data], {
               grid: {
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
           });
           /* END BAR CHART */


       });

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
       
       $(function () {
         $('[data-toggle="popover"]').popover()
       })

       //----end document ready

   });