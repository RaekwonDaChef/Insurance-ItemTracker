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

function navigateTo(linkElement) {
    var pageName = linkElement.slice(5); // remove 'link_' from linkElement
    var containerElement = 'container_'+pageName;
    var tableElement = 'table_'+pageName;
    var tableNavElement = '#tableNav_'+pageName;
    var tableTitle;
    var tableItemCount;
    
    // search box should be empty, if user is navigating between pages
    if (linkElement != "link_search") { $('#searchBox').val(''); }
    // if a success message is shown, it should be hidden/cleared, if user is navigating between pages
    $("#successMessage").hide();
    $("nav").find(".active").removeClass("active"); // remove active class from any nav element
    
    $.getJSON('includes/pages.json.php', { page: pageName }, function(data) { // get page info (json)
        tableTitle = data.title; // store page title in a variable for use outside of this scope
        document.title = "Item Tracker (" + data.title + ")"; // set page title
        history.pushState(null, null, data.pushStateAddr); // change address bar url address
    });
    
    hideSelected();

    $("#statusChangeNotReplaced, #statusChangePartial, #statusChangeReplaced").hide();
    $("#submitReceipts, #finalizeReceipts").hide(); // hide both buttons (Finalize & Submit)
    
    switch (pageName) {
        case "notreplaced":
            $("#statusDropdown").show().html("Status: Not Replaced");
            $("#statusChangePartial, #statusChangeReplaced").show();
        break;
        case "partial":
            $("#statusDropdown").show().html("Status: Partial");
            $("#statusChangeNotReplaced, #statusChangeReplaced").show();
        break;
        case "replaced":
            $("#submitReceipts").show();  // show button: "Submit Receipts"
            $("#statusDropdown").show().html("Status: Replaced");
            $("#statusChangeNotReplaced, #statusChangePartial").show();
        break;
        case "submitted":
            $("#finalizeReceipts").show(); // show button: "Finalize Receipts"
        break;
        default:
            $("#statusDropdown").hide();
    }
    
    
    if (pageName !== 'stats') { // if it is not the stats page, then it is a table page...
        // Top navbar has only 2 text links: Home & View Items dropdown
        // one is always active while the other is not:
        $("#link_items_li").addClass("active");
        $("#link_stats_li").removeClass("active");
        $("header").slideUp(); // hide the header / jumbotron on table pages
        $(tableNavElement).addClass("active"); // table nav bar, set current link as active
        $("#tableNav").show();
        $("#sortOrderMenuButton").show(); // dropdown menu - asc or desc
        $("#sortOrderByMenuButton").show(); // dropdown menu - column sort by seletion
        $("#navTextTitle").show(); /* "Item Tracker" text on top navbar which only appears on
                                                                pages that do not have header / jumbotron */

        
        //if ($("[id^='"+containerElement+"']").is(':empty')) { // if that table has not been loaded yet...
        /*if (pageName == 'submitted') {
            
        } else*/ if (pageName !== 'search') {
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
    } else {
        $("header").slideDown();
        $("#navTextTitle").hide();
        $("#tableNav").hide();
        $("#sortOrderMenuButton, #sortOrderByMenuButton").hide();
        $("#link_items_li").removeClass("active");
        $("#link_stats_li").addClass("active");
        $("#submitReceipts, #finalizeReceipts").hide();
        barChart.destroy();
        pieChart.destroy();
        loadCharts();
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
    var tableTitle;
    var tableItemCount;
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
        case "unitprice":
            $("#sortOrderByMenuButton").html("Sort By: Price");
            localStorage.tableOrderBy = "unitprice";
        break;
        case "spendamount":
            $("#sortOrderByMenuButton").html("Sort By: Spend");
            localStorage.tableOrderBy = "spendamount";
        break;
        case "collectamount":
            $("#sortOrderByMenuButton").html("Sort By: $$");
            localStorage.tableOrderBy = "collectamount";
        break;
        case "qty":
            $("#sortOrderByMenuButton").html("Sort By: Quantity");
            localStorage.tableOrderBy = "qty";
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