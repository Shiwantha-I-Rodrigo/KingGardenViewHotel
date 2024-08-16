document.getElementById("print_btn").addEventListener("click", function (event) {
    var page = document.getElementById("print_page");
    newWin = window.open("");
    newWin.document.write('<style> table, th {border:2px solid black; border-collapse : collapse;} }</style>' + page.outerHTML);
    newWin.print();
    newWin.close();
});