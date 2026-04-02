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

$(function() {

    $('.card').hover(function() { 
        const country = $(this).attr('id').split('_')[1];
        $('.card[id^="card_' + country + '_"]').addClass('highlight');
    }, function(){
        const country = $(this).attr('id').split('_')[1];
        $('.card[id^="card_' + country + '_"]').removeClass('highlight');
    });

    generateconnectors();

    window.addEventListener('resize', redrawThrottle);
    window.addEventListener('scroll', redrawThrottle);
});

let ticking = false;
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


function generateconnectors() {
    if($('#item_' + annees[0]).width() > window.innerWidth/2){ 
        return;
      }
    $('.card', '#item_' + annees[0]).each(function() {
        const country = $(this).attr('id').split('_')[1];
        annees.forEach((year, idx) => {
            if(annees[idx+1]){
                const satis = $('#satis_' + country + '_' + year).text()*1;
                connectElements('#card_' + country + '_' + year, '#card_' + country + '_' + annees[idx+1], satis);
            }
        });

    });

}

function connectElements(selector1, selector2, width) {
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
    path.setAttribute("stroke-width", 4);
    path.setAttribute("fill", "none");

    svg.appendChild(path);
}

function redraw() {
    const svg = document.getElementById("connections");
    if (!svg) return;

    svg.innerHTML = "";
    generateconnectors();
}

