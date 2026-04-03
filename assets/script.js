const pays_couleur = {
  "AUT": "rgba(215, 20, 26, 0.5)",
  "BEL": "rgba(255, 215, 0, 0.5)",
  "DEU": "rgba(0, 0, 0, 0.5)",
  "ESP": "rgba(170, 21, 27, 0.5)",
  "EST": "rgba(0, 114, 206, 0.5)",
  "FIN": "rgba(0, 53, 128, 0.5)",
  "FRA": "rgba(0, 85, 164, 0.5)",
  "GRC": "rgba(13, 94, 175, 0.5)",
  "IRL": "rgba(22, 155, 98, 0.5)",
  "ITA": "rgba(0, 140, 69, 0.5)",
  "LTU": "rgba(253, 185, 19, 0.5)",
  "LUX": "rgba(0, 161, 222, 0.5)",
  "LVA": "rgba(158, 48, 57, 0.5)",
  "NLD": "rgba(33, 70, 139, 0.5)",
  "PRT": "rgba(0, 102, 0, 0.5)",
  "SVK": "rgba(0, 91, 187, 0.5)"
};

let ticking = false;

$(function() {

    $('.card').on('click', function() {
        const country = $(this).attr('id').split('_')[1];
        const countryname = $(this).find('.card-title h3').text();

        // graph finance
        let serie1chart1 = {
            name: 'Dette/hab', 
            data: [], 
            showInLegend:true, 
            color: 'rgba(0,0,0,.4)', 
            borderColor: 'rgba(0,0,0,1)',
            dataLabels: {
                allowOverlap:false,
                style: {
                color: 'rgba(0,0,0,1)',
                fontSize: '10px',
                textOutline: false 
                }
            }
        };

        let serie2chart1 = {
            name: 'Impôts/hab', 
            data: [], 
            showInLegend:true, 
            color: 'rgba(255,0,0,.4)', 
            borderColor: 'rgba(255,0,0,1)',
            dataLabels: {
                allowOverlap:false,
                style: {
                color: 'rgba(255,0,0,1)',
                fontSize: '10px',
                textOutline: false 
                }
            }
        };

        let serie3chart1 = {
            name: 'Budget/hab', 
            data: [], 
            showInLegend:true, 
            color: 'rgba(0, 89, 206, 0.4)', 
            borderColor: 'rgb(0, 89, 206)',
            dataLabels: {
                allowOverlap:false,
                style: {
                color:'rgba(0, 89, 206, 1)',
                fontSize: '10px',
                textOutline: false 
                }
            }
        };

        let serie1chart2 = {
            name: 'Moins de 15 ans', 
            data: [], 
            showInLegend:true, 
            shadow:false,
            color: 'rgba(252, 143, 0,.4)', 
            borderColor: 'rgb(252, 143, 0)',
            dataLabels: {
                allowOverlap:false,
                style: {
                color: 'rgba(252, 143, 0,1)',
                fontSize: '10px',
                textOutline: false 
                }
            }
        };
        let serie2chart2 = {
            name: 'De 15 à 64 ans', 
            data: [], 
            showInLegend:true, 
            color: 'rgba(238, 0, 186,.4)', 
            borderColor: 'rgb(238, 0, 186)',
            dataLabels: {
                allowOverlap:false,
                style: {
                color: 'rgba(238, 0, 186,1)',
                fontSize: '10px',
                textOutline: false 
                }
            }
        };
        let serie3chart2 = {
            name: '65 ans et plus', 
            data: [], 
            showInLegend:true, 
            color: 'rgba(0,0,0,.4)', 
            borderColor: 'rgba(0,0,0,1)',
            dataLabels: {
                allowOverlap:false,
                style: {
                color: 'rgba(0,0,0,1)',
                fontSize: '10px',
                textOutline: false 
                }
            }
        };

        const imgdrapeau =`<img class='drapeau' src='data/drapeaux/${country}.svg' alt="${countryname}" />`;
        $('#modal').html('<h2>' +imgdrapeau +  countryname +  imgdrapeau +'</h2><h3>'+ countryname +'- Finances</h3><div id="chart1"></div><hr><h3>'+ countryname +' - Démographie</h3><div id="chart2"></div>');
        let min1 = 0;
        let max1 = 0;

        data_clst.forEach(clst => {
            if ( clst.cde_pays === country ) {
                if ( clst.dette_par_hab*-1 < min1 ) {
                    min1 = clst.dette_par_hab*-1;
                }
                if ( clst.impot_hab*-1 < min1 ) {
                    min1 = clst.impot_hab*-1;
                }
                if( clst.depense_par_hab > max1 ) {
                    max1 = clst.depense_par_hab;
                }

                serie1chart1.data.push({
                    //name: clst.annee,
                    y: clst.dette_par_hab*-1,
                    x: clst.annee
                });
                serie2chart1.data.push({
                    //name: clst.annee,
                    y: clst.impot_hab*-1,
                    x: clst.annee
                });
                serie3chart1.data.push({
                    //name: clst.annee,
                    y: clst.depense_par_hab,
                    x: clst.annee
                });

                serie1chart2.data.push({
                    //name: clst.annee,
                    y: clst.moins_15ans,
                    x: clst.annee
                });
                serie2chart2.data.push({
                    //name: clst.annee,
                    y: clst.entre_15_64,
                    x: clst.annee
                });
                serie3chart2.data.push({
                    //name: clst.annee,
                    y: clst.sup_64,
                    x: clst.annee
                });

            }
        });

        $('#modal').modal();
        drawchart1([serie1chart1, serie2chart1, serie3chart1], {min:min1, max:max1});
        drawchart2([serie1chart2, serie2chart2, serie3chart2], {min:0, max:100});
    });
    
    function drawchart1(serie, params) {

        Highcharts.chart('chart1', {
                credits: { enabled:false },
                chart: {
                    type: 'column',
                    height:400,
                    marginTop:20
                },
                title: {
                    text: undefined
                },
                legend:{
                    enabled: true,
                    floating: true,
                    verticalAlign: 'top',
                    align: 'right',
                    y:-15
                },
                xAxis: {
                    tickPositioner: function() {
                        return this.series[0].xData;
                    },
                    labels: {
                        //rotation: -45
                    }
                },
                yAxis: {
                        min: params.min,
                        max: params.max,
                        title: {
                            text: '€ par habitant'
                        }
                },
                plotOptions: {
                    series: {
                        dataLabels: {
                            enabled: true,
                            formatter: function() {
                                return new Intl.NumberFormat('fr-FR',{minimumFractionDigits: 0, maximumFractionDigits: 0}).format(this.y);
                            }
                        },
                        tooltip: {
                            pointFormatter: function() {
                                return '<span style="color:' + this.color + '">\u25CF</span> ' + this.series.name + ': <b>' + new Intl.NumberFormat('fr-FR',{minimumFractionDigits: 0, maximumFractionDigits: 0}).format(this.y) + ' €</b><br/>';
                            }
                        },
                    }
                },

                series : serie
            });
    }

    function drawchart2(serie, params) {
        Highcharts.chart('chart2', {
                credits: { enabled:false },
                chart: {
                    type: 'column',
                    height:400,
                    marginTop:20
                },
                title: {
                    text: undefined
                },
                legend:{
                    enabled: true,
                    floating: true,
                    verticalAlign: 'top',
                    align: 'right',
                    y:-15
                },
                xAxis: {
                    tickPositioner: function() {
                        return this.series[0].xData;
                    },
                    labels: {
                        //rotation: -45
                    }
                },
                yAxis: {
                        min: params.min,
                        max: params.max,
                        title: {
                            text: '% de la population'
                        }
                },
                plotOptions: {
                    series: {
                        dataLabels: {
                            enabled: true,
                            formatter: function() {
                                return new Intl.NumberFormat('fr-FR',{minimumFractionDigits: 0, maximumFractionDigits: 0}).format(this.y)+ ' %'
                            }
                        },
                        tooltip: {
                            pointFormatter: function() {
                                return '<span style="color:' + this.color + '">\u25CF</span> ' + this.series.name + ': <b>' + new Intl.NumberFormat('fr-FR',{minimumFractionDigits: 0, maximumFractionDigits: 0}).format(this.y) + ' %</b><br/>';
                            }
                        },
                    }
                },

                series : serie
            });
    }

    $('.card').hover(function() { 
        const country = $(this).attr('id').split('_')[1];
        $('.card[id^="card_' + country + '_"]').addClass('highlight');
        connectCountry(country);
    }, function(){
        const country = $(this).attr('id').split('_')[1];
        $('.card[id^="card_' + country + '_"]').removeClass('highlight');
        ticking = false; // reset pour éviter un redraw inutile
        redrawThrottle();
    });

    generateconnectors();

    window.addEventListener('resize', redrawThrottle);
    window.addEventListener('scroll', redrawThrottle);
});



function redrawThrottle() {
    if (!ticking) {
        requestAnimationFrame(() => {
            const svg = document.getElementById("connections");
            if (!svg) return;

            svg.innerHTML = "";
            generateconnectors();

            ticking = false;
        });
        ticking = true;
    }
}

function connectCountry(country) {
        annees.forEach((year, idx) => {
            if(annees[idx+1]){
                connectElements('#card_' + country + '_' + year, '#card_' + country + '_' + annees[idx+1], 25);
            }
        });
}

function generateconnectors() {
    if($('#item_' + annees[0]).width() > window.innerWidth/2){ 
        return;
      }
    $('.card', '#item_' + annees[0]).each(function() {
        const country = $(this).attr('id').split('_')[1];
        annees.forEach((year, idx) => {
            if(annees[idx+1]){
                const satis = $('#satis_' + country + '_' + year).text()*1;
                connectElements('#card_' + country + '_' + year, '#card_' + country + '_' + annees[idx+1], 4);
            }
        });

    });

}

function connectElements(selector1, selector2, width=4) {
    const strokecolor = pays_couleur[selector1.split('_')[1]] || '#333';
    let svg = document.getElementById("connections");

    if (!svg) {
        svg = document.createElementNS("http://www.w3.org/2000/svg", "svg");

        svg.setAttribute("id", "connections");
        svg.style.position = "absolute";
        svg.style.top = "0";
        svg.style.left = "0";
        svg.style.pointerEvents = "none";
        svg.style.zIndex = "0";

        document.body.appendChild(svg);
    }

    // 🔥 IMPORTANT : taille du document (pas viewport)
    const docWidth = Math.max(
        document.body.scrollWidth,
        document.documentElement.scrollWidth
    );

    const docHeight = Math.max(
        document.body.scrollHeight,
        document.documentElement.scrollHeight
    );

    svg.setAttribute("width", docWidth);
    svg.setAttribute("height", docHeight);

    const el1 = document.querySelector(selector1);
    const el2 = document.querySelector(selector2);

    if (!el1 || !el2) return;

    const r1 = el1.getBoundingClientRect();
    const r2 = el2.getBoundingClientRect();

    // 🔥 coordonnées document
    /*const x1 = r1.left + window.scrollX + r1.width / 2;
    const y1 = r1.top + window.scrollY + r1.height / 2;

    const x2 = r2.left + window.scrollX + r2.width / 2;
    const y2 = r2.top + window.scrollY + r2.height / 2;
    */
    const x1 = r1.right + window.scrollX;
    const y1 = r1.top + window.scrollY + r1.height / 2;

    const x2 = r2.left + window.scrollX;
    const y2 = r2.top + window.scrollY + r2.height / 2;

    const dx = Math.abs(x2 - x1) * 0.5;

    const path = document.createElementNS("http://www.w3.org/2000/svg", "path");

    const d = `M ${x1} ${y1}
               C ${x1 + dx} ${y1},
                 ${x2 - dx} ${y2},
                 ${x2} ${y2}`;

    path.setAttribute("d", d);
    path.setAttribute("stroke", strokecolor);
    path.setAttribute("stroke-width", width);
    path.setAttribute("fill", "none");

    svg.appendChild(path);
}

function redraw() {
    const svg = document.getElementById("connections");
    if (!svg) return;

    svg.innerHTML = "";
    generateconnectors();
}

