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

var tableSortOrders = [ // all possible table sort orders (columns)
    {name: "description"},{name: "unitprice"},{name: "spendamount"},{name: "status"},
    {name: "collectamount"},{name: "qty"},{name: "item"}
];

if (!tableSortOrders.some(tableOrder => tableOrder.name === localStorage.tableOrderBy)) {
    /* if the localStorage variable that is set is not found in the array of possibilities, then set default */
    localStorage.tableOrderBy = "item";                              
}

if (localStorage.tableOrder != "desc" && localStorage.tableOrder != "asc") { // if order is not valid...
    localStorage.tableOrder = "asc"; // set default
}

// credit: https://www.sitepoint.com/url-parameters-jquery/
$.urlParam = function(name) {
    var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
    if (results==null) {
       return null;
    } else {
       return results[1] || 0;
    }
}

function loadSiteData(data) {
    $.getJSON('output/submissions.php', { pending: "show" }, function(data) {
        if (data.all < 1) {
            $("#link_submissions").hide();
        } else {
            if (data.pending > 0) {
                $("#submissionCount").html(data.pending);
            } else {
                $("#submissionCount").hide();
            }
        }
    });
    var allItems = 
        data.partial.total +
        data.finalized.total +
        data.notreplaced.total +
        data.replaced.total +
        data.submitted.total;
    if (allItems < 1) { $("#welcomeNoItems").fadeIn(2000); }
    if (data.partial.total > 0) {
        // add item count to navigation link text 
        $("#link_partial, #tableNav_partial")
            .html('Partial <span class="badge badge-dark ml-1">'+data.partial.total+'</span>')
            .removeClass("disabled"); 
    } else {
        $("#link_partial, #tableNav_partial").html('Partial').addClass("disabled");
    }
    if (data.finalized.total > 0) {
        $("#link_finalized, #tableNav_finalized")
            .html('Finalized <span class="badge badge-dark ml-1">'+data.finalized.total+'</span>')
            .removeClass("disabled");
    } else {
        $("#link_finalized, #tableNav_finalized").html('Finalized').addClass("disabled");
    }
    if (data.notreplaced.total > 0) {
        $("#link_notreplaced, #tableNav_notreplaced")
            .html('Not Replaced <span class="badge badge-dark ml-1">'+data.notreplaced.total+'</span>')
            .removeClass("disabled"); 
    } else {
        $("#link_notreplaced, #tableNav_notreplaced").html('Not Replaced').addClass("disabled");
    }
    if (data.replaced.total > 0) {
        $("#link_replaced, #tableNav_replaced").html('Replaced <span class="badge badge-dark ml-1">'+data.replaced.total+'</span>').removeClass("disabled");
    } else {
        $("#link_replaced, #tableNav_replaced").html('Replaced').addClass("disabled");
    }
    if (data.submitted.total > 0) {
        $("#link_submitted, #tableNav_submitted").html('Submitted <span class="badge badge-dark ml-1">'+data.submitted.total+'</span>').removeClass("disabled");
    } else {
        $("#link_submitted, #tableNav_submitted").html('Submitted').addClass("disabled");
    }
    $("#link_all, #tableNav_all").html('All <span class="badge badge-dark ml-1">'+allItems+'</span>');
}

function showSearchResults(str) {
    localStorage.searchQuery = str; // used for reloadTable and ReSortTable
    if ($.urlParam('view') != "search") {
        // if the page has not gone to ?view=search, then a search has begun and we need to
        // save the current page so we can navigate back to it when search is over
        localStorage.viewBeforeSearch = $.urlParam('view');
    }
    if (str.length<1) { // if user has cleared the search box
        // reshow page based on url with $.urlParam
        $("#container_searchresults").html(''); // remove search results
        $("#searchCaption").hide();
        // navigate to page viewed before search began...
        if (localStorage.viewBeforeSearch != "null") {
            //var t0 = performance.now();
            navigateTo('link_'+localStorage.viewBeforeSearch);
            //var t1 = performance.now();
            //console.log("Call to navigateTo took " + (t1 - t0) + " milliseconds.");
        } else {
            // stats (index) has no ?view= url and therefore will be stored as null in localStorage
            navigateTo('link_stats');
        }
        return;
    }
    
    // show loading image
    $("#container_searchresults").html("<img class=loading src=output/images/tables/loading.gif>");
    // load search results table into the search results page container
    $.ajax({
        url: 'output/table.php?table=search&orderby='+localStorage.tableOrderBy+'&order='+localStorage.tableOrder+'&query='+str,
        type: 'GET',
        success: function(responseText) {
            $("#searchCaption").show();
            $("#container_searchresults").html(responseText);
        },
        error: function(xhr) {
            console.log("AJAX Call Error: " + xhr.status + " " + xhr.statusText);
        }
    });
    navigateTo("link_search"); // navigate to link page
}

function reloadSite() { // called when items are submitted/finalized, and for item status changes
    $.getJSON('includes/items.json.php', { type: "stats" }, function(data) {
        // get site stats data through JSON, data object is injected
        // into site elements and charts are created
        switch ($.urlParam('view')) { // reload table if user is viewing one
            case "all": reloadTable('all'); break;
            case "partial": reloadTable('partial'); break;
            case "finalized": reloadTable('finalized'); break;
            case "notreplaced": reloadTable('notreplaced'); break;
            case "replaced": reloadTable('replaced'); break;
            case "submitted": reloadTable('submitted'); break;
        }
        loadSiteData(data); // function that adds the item totals for each status into nav bar links text
        barChart.destroy();
        pieChart.destroy();
        loadCharts(); // destroy and recreate charts
    });
}

$(document).ready(function() {
    var height = $(window).height() - 50;
    $("#pageContainer").height(height);
    
    $(window).resize(function(){
        var checkedTotal = $("input:checked").length;
        var heightOffset = 0;
        if (checkedTotal < 1) { heightOffset = 50; } else { heightOffset = 95; }
        height = $(window).height() - heightOffset;
        $("#pageContainer").height(height);
    });
    
    $.getJSON('includes/items.json.php', { type: "stats" }, function(data) {
        switch ($.urlParam('view')) { // ensure that all links can be accessed via direct url link
            case "all": navigateTo('link_all'); break;
            case "partial": if (data.partial.total > 0) { navigateTo('link_partial'); } else { navigateTo('link_all'); alert('No partial items!'); = } break;
            case "finalized": if (data.finalized.total > 0) { navigateTo('link_finalized'); } else { navigateTo('link_all'); alert('No finalized items!'); } break;
            case "notreplaced": if (data.notreplaced.total > 0) { navigateTo('link_notreplaced'); } else { navigateTo('link_all'); alert('No not replaced items!'); } break;
            case "replaced": if (data.replaced.total > 0) { navigateTo('link_replaced'); } else { navigateTo('link_all'); alert('No replaced items!'); } break;
            case "submitted": if (data.submitted.total > 0) { navigateTo('link_submitted'); } else { navigateTo('link_all'); alert('No submitted items!'); } break;
            case "submissions": navigateTo('link_submissions'); break;
            case "search": navigateTo('link_search'); break;
            default: 
                $("header").slideDown(); // only show header when not on a table view page
                $("#tableNav").hide();
                $("#sortOrderMenuButton").hide();
                $("#sortOrderByMenuButton").hide();
        }
        loadSiteData(data);
    });
    
    $("#searchBox").keyup(function() {
        showSearchResults($(this).val());
    });
    
    switch (localStorage.tableOrder) {
        case "asc": $("#sortOrderMenuButton").html("Asc. (Low to High)"); break;
        case "desc": $("#sortOrderMenuButton").html("Desc. (High to Low)"); break;
    }
    
    switch (localStorage.tableOrderBy) {
        case "description": $("#sortOrderByMenuButton").html("Sort By: Description"); break;
        case "unitprice": $("#sortOrderByMenuButton").html("Sort By: Price"); break;
        case "spendamount": $("#sortOrderByMenuButton").html("Sort By: Spend"); break;
        case "collectamount": $("#sortOrderByMenuButton").html("Sort By: $$"); break;
        case "qty": $("#sortOrderByMenuButton").html("Sort By: Quantity"); break;
        case "item": $("#sortOrderByMenuButton").html("Sort By: Item #"); break;
        case "status": $("#sortOrderByMenuButton").html("Sort By: Status"); break;
    }
    
    $("nav .nav-link").on("click", function(event) {
        if (event.target.id != "navbarDropdown") {
            $(".nav").find(".active").removeClass("active");
            $(this).addClass("active");
        }
    });
    
    $("#link_logo, #link_stats").click(function(event) {
        event.preventDefault(); navigateTo('link_stats');
    });
    $("#link_all, #tableNav_all").click(function(event) {
        event.preventDefault(); navigateTo('link_all');
    });
    $("#link_notreplaced, #tableNav_notreplaced").click(function(event) {
        event.preventDefault(); navigateTo('link_notreplaced');
    });
    $("#link_partial, #tableNav_partial").click(function(event) {
        event.preventDefault(); navigateTo('link_partial');
    });
    $("#link_replaced, #tableNav_replaced").click(function(event) {
        event.preventDefault(); navigateTo('link_replaced')
    });
    $("#link_submitted, #tableNav_submitted").click(function(event) {
        event.preventDefault(); navigateTo('link_submitted');
    });
    $("#link_finalized, #tableNav_finalized").click(function(event) {
        event.preventDefault(); navigateTo('link_finalized');
    });

    $('[data-toggle="tooltip"]').tooltip(); // enable tooltips
    
    $('a') // force all external site links to open in a new tab/window
        .filter('[href^="http"], [href^="//"]')
         .not('[href*="' + window.location.host + '"]')
        .attr('rel', 'noopener noreferrer')
        .attr('target', '_blank');
});