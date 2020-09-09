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

// Single-Page-App (SPA) structure

// 'popstate' event is fired when user navigates site using back/forward buttons
$(window).bind('popstate',  
    function(event) {
        // this function gets the new page from the url parameter 'view'
        // and loads it, giving the browsers back and forward buttons functionality
        switch ($.urlParam('view')) {
            case "all": navigateTo('link_all', false); break;
            case "partial": navigateTo('link_partial', false); break;
            case "finalized": navigateTo('link_finalized', false); break;
            case "notreplaced": navigateTo('link_notreplaced', false); break;
            case "replaced": navigateTo('link_replaced', false); break;
            case "submitted": navigateTo('link_submitted', false); break;
            case "submissions": navigateTo('link_submissions', false); break;
            case "search": navigateTo('link_search', false); break;
            default:
                navigateTo('link_stats', false); break;
        }
    }
);

function navigateTo(linkElement, doPushState = true) {
    var pageName = linkElement.slice(5); // remove 'link_' from linkElement
    var containerElement = 'container_'+pageName;
    var tableElement = 'table_'+pageName;
    var tableNavElement = '#tableNav_'+pageName;
    
    if (linkElement != "link_search") { $('#searchBox').val(''); $("#searchCaption").hide(); } // search box should be empty
    $("#successMessage").hide(); // if a success message is shown, it should be hidden/cleared
    $("nav").find(".active").removeClass("active"); // remove active class from any nav element
    hideSelected(); // hide the selected item(s) footer bar
    if (pageName !== 'replaced') { $("#submitReceipts").hide(); } // hide 'Submit Receipts' button if not on Replaced page
    if (pageName !== 'notreplaced' && pageName !== 'partial' && pageName !== 'replaced') {
        $("#statusDropdown").hide(); // the status change dropdown should only appear on 3 pages (Not Replaced, Partial & Replaced)
    }
    
    // #link_submissions_li = "Submissions" link @ top nav bar
    // #link_items_li = "View Items" dropdown link @ top nav bar
    // #link_stats_li = "Home" link @ top nav bar
    
    if (pageName == 'notreplaced' ||
        pageName == 'partial' ||
        pageName == 'replaced' ||
        pageName == 'submitted' ||
        pageName == 'finalized' ||
        pageName == 'all') { // if item page
        $("#link_items_li").addClass("active");
        $(tableNavElement).addClass("active"); // table nav bar, set current link as active
        $("#tableNav").show(); // table navigation bar that appears below main top nav bar
        $("#sortOrderMenuButton").show(); // dropdown menu - asc or desc
        $("#sortOrderByMenuButton").show(); // dropdown menu - column sort by seletion
    } else { // not an item page, must be submissions page or stats
        $("#tableNav").hide(); // table navigation bar that appears below main top nav bar
        $("#sortOrderMenuButton").hide(); // dropdown menu - asc or desc
        $("#sortOrderByMenuButton").hide(); // dropdown menu - column sort by selection
    }
    
    switch (pageName) {
        case "notreplaced":
            // -- item status change dropdown for footer bar --------------------
            $("#statusDropdown").show().html("Status: Not Replaced");
            $("#statusChangePartial, #statusChangeReplaced").show();
            // ------------------------------------------------------------------
            if (doPushState === true) { history.pushState(null, null, "index.php?view=notreplaced"); } // change address bar url address
        break;
        case "partial":
            // -- item status change dropdown for footer bar --------------------
            $("#statusDropdown").show().html("Status: Partial");
            $("#statusChangeNotReplaced, #statusChangeReplaced").show();
            // ------------------------------------------------------------------
            if (doPushState === true) { history.pushState(null, null, "index.php?view=partial"); } // change address bar url address
        break;
        case "replaced":
            $("#submitReceipts").show();  // show button: "Submit Receipts"
            // -- item status change dropdown for footer bar --------------------
            $("#statusDropdown").show().html("Status: Replaced");
            $("#statusChangeNotReplaced, #statusChangePartial").show();
            // ------------------------------------------------------------------
            if (doPushState === true) { history.pushState(null, null, "index.php?view=replaced"); } // change address bar url address
        break;
        case "submitted":
            if (doPushState === true) { history.pushState(null, null, "index.php?view=submitted"); } // change address bar url address
        break;
        case "finalized":
            if (doPushState === true) { history.pushState(null, null, "index.php?view=finalized"); } // change address bar url address
        break;
        case "all":
            if (doPushState === true) { history.pushState(null, null, "index.php?view=all"); } // change address bar url address
        break;
        case "submissions":
            $("#link_submissions_li").addClass("active");
            if (doPushState === true) { history.pushState(null, null, "index.php?view=submissions"); } // change address bar url address
        break;
        default: // stats
            $("#link_stats_li").addClass("active");
            $("header").slideDown();
            $("#navTextTitle").hide();
            barChart.destroy();
            pieChart.destroy();
            loadCharts();
            if (doPushState === true) { history.pushState(null, null, "index.php"); } // change address bar url address
    }
    
    if (pageName !== 'stats') { // if it is not the stats page...
        $("header").slideUp(); // hide the header / jumbotron on table pages
        $("#navTextTitle").show(); // "Item Tracker" text on top navbar
        if ($("[id^='"+containerElement+"']").is(':empty')) { // if that page has not been loaded yet...
            if (pageName == 'submissions') {
                // show loading image temporarily while the table loads:
                $("[id^='"+containerElement+"']").html("<img class=loading src=output/images/tables/loading.gif>"); 
                $.ajax({
                    url: 'output/submissions.php',
                    type: 'GET',
                    success: function(responseText) {
                        $("[id^='"+containerElement+"']").html(responseText); // load table html
                    },
                    error: function(xhr) {
                        console.log("AJAX Call Error: " + xhr.status + " " + xhr.statusText);
                    }
                });
            } else if (pageName !== 'search') {
                // show loading image temporarily while the table loads:
                $("[id^='"+containerElement+"']").html("<img class=loading src=output/images/tables/loading.gif>"); 
                $.ajax({
                    url: 'output/table.php?table='+pageName+'&orderby='+localStorage.tableOrderBy+'&order='+localStorage.tableOrder,
                    type: 'GET',
                    success: function(responseText) {
                        $("[id^='"+containerElement+"']").html(responseText); // load table html
                    },
                    error: function(xhr) {
                        console.log("AJAX Call Error: " + xhr.status + " " + xhr.statusText);
                    }
                });
            }
        }
    }
    // hide and remove the current container, indentified by its css class
    // which is only applied when the page is navigated to / shown
    $(".show-content").hide().removeClass("show-content");
    $("[id^='"+containerElement+"']").show().addClass("show-content"); // show the right container
}

function reloadTable(table) {
	var containerElementName = 'container_'+table;
	
    // show loading image temporarily while the table loads:
    $("[id^='"+containerElementName+"']").html("<img class=loading src=output/images/tables/loading.gif>"); 
    
    var searchQueryURL = ""; if (table == "search") { searchQueryURL = "query="+localStorage.searchQuery+"&"; }

    $.ajax({
        url: 'output/table.php?'+searchQueryURL+'table='+table+
        '&orderby='+localStorage.tableOrderBy+'&order='+localStorage.tableOrder,
        type: 'GET',
        success: function(responseText) {
            $("[id^='"+containerElementName+"']").html(responseText);
        },
        error: function(xhr) {
            console.log("AJAX Call Error: " + xhr.status + " " + xhr.statusText);
        }
    });
}

function ReSortTable(orderBy, order) {
    var table = $.urlParam('view');
	var containerElementName = 'container_'+table;
    switch (order) {
        case "asc":
            $("#sortOrderMenuButton").html("Asc. (Low to High)");
            localStorage.tableOrder = "asc";
        break;
        case "desc":
            $("#sortOrderMenuButton").html("Desc. (High To Low)");
            localStorage.tableOrder = "desc";
        break;
    }
    switch (orderBy) {
        case "description":
            $("#sortOrderByMenuButton").html("Sort By: Description");
            localStorage.tableOrderBy = "description";
        break;
        case "unit_price":
            $("#sortOrderByMenuButton").html("Sort By: Price");
            localStorage.tableOrderBy = "unit_price";
        break;
        case "spend_amount":
            $("#sortOrderByMenuButton").html("Sort By: Spend");
            localStorage.tableOrderBy = "spend_amount";
        break;
        case "collect_amount":
            $("#sortOrderByMenuButton").html("Sort By: $$");
            localStorage.tableOrderBy = "collect_amount";
        break;
        case "quantity":
            $("#sortOrderByMenuButton").html("Sort By: Quantity");
            localStorage.tableOrderBy = "quantity";
        break;
        case "item":
            $("#sortOrderByMenuButton").html("Sort By: Item #");
            localStorage.tableOrderBy = "item";
        break;
        case "status":
            $("#sortOrderByMenuButton").html("Sort By: Status");
            localStorage.tableOrderBy = "status";
        break;//query=string&
    }
    var searchQueryURL = ""; if (table == "search") { searchQueryURL = "query="+localStorage.searchQuery+"&"; }
    //alert('output/table.php?table='+table+'&orderby='+localStorage.tableOrderBy+'&order='+localStorage.tableOrder);
    $.ajax({
        url: 'output/table.php?'+searchQueryURL+'table='+table+
        '&orderby='+localStorage.tableOrderBy+'&order='+localStorage.tableOrder,
        type: 'GET',
        success: function(responseText) {
            $("[id^='"+containerElementName+"']").html(responseText);
        },
        error: function(xhr) {
            console.log("AJAX Call Error: " + xhr.status + " " + xhr.statusText);
        }
    });
}